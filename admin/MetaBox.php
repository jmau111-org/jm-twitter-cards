<?php

namespace JMTC\Admin;

use JMTC\Functions;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class MetaBox
{
    private $tc_opts;

    use Functions;

    public function __construct()
    {
        $this->tc_opts = $this->get_options();
    }

    private function get_form_settings(): array
    {
        $cardTypeGeneral = (!empty($this->tc_opts['twitterCardType'])) ? $this->tc_opts['twitterCardType'] : '';
        $cardType = (get_post_meta($post_id, 'twitterCardType', true)) ? get_post_meta($post_id, 'twitterCardType', true) : $cardTypeGeneral;
        $post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;

        return [
            [
                'method' => 'wrapper', 
                'tag' => 'table', 
                'class' => 'form-table', 
                'where' => 'start'
            ],
            [
                'method' => 'wrapper', 
                'tag' => 'tbody', 
                'where' => 'start'
            ],
            [
                'method'   => 'select',
                'label'    => esc_html__('Card type', 'jm-tc'),
                'field_id' => 'twitterCardType',
                'options'  => [
                    'summary'             => esc_html__('Summary', 'jm-tc'),
                    'summary_large_image' => esc_html__('Summary below Large Image', 'jm-tc'),
                    'player'              => esc_html__('Player', 'jm-tc'),
                    'app'                 => esc_html__('Application', 'jm-tc'),
                ],
                'type'     => 'select',
                'value'    => $cardType,
            ],
            [
                'method'   => 'image',
                'field_id' => 'cardImage',
                'label'    => esc_html__('Set another source as twitter image (enter URL)', 'jm-tc'),
                'value'    => get_post_meta($post_id, 'cardImage', true),
            ],
            [
                'method'    => 'textarea',
                'field_id'  => 'cardImageAlt',
                'label'     => esc_html__('Image Alt', 'jm-tc'),
                'value'     => get_post_meta($post_id, 'cardImageAlt', true),
                'charcount' => 420,
            ],
            [
                'method'   => 'url',
                'field_id' => 'cardPlayer',
                'label'    => esc_html__('URL of iFrame player (MUST BE HTTPS)', 'jm-tc'),
                'value'    => get_post_meta($post_id, 'cardPlayer', true),
            ],
            [
                'method'   => 'number',
                'field_id' => 'cardPlayerWidth',
                'label'    => esc_html__('Player width', 'jm-tc'),
                'min'      => 262,
                'max'      => 1000,
                'step'     => 1,
                'value'    => get_post_meta($post_id, 'cardPlayerWidth', true),
            ],
            [
                'method'   => 'number',
                'field_id' => 'cardPlayerHeight',
                'label'    => esc_html__('Player height', 'jm-tc'),
                'type'     => 'number',
                'min'      => 196,
                'max'      => 1000,
                'step'     => 1,
                'value'    => get_post_meta($post_id, 'cardPlayerHeight', true),
            ],
            [
                'method' => 'wrapper', 
                'tag' => 'tbody', 
                'where' => 'hyperspace'
            ],
            [
                'method' => 'wrapper', 
                'tag' => 'table', 
                'where' => 'hyperspace'
            ],
        ];
    }

    public function add_box(): void
    {
        add_meta_box(
            'jm_tc_metabox',
            esc_html__('Twitter Cards', 'jm-tc'),
            [$this, 'display_box'],
            $this->get_post_types()
        );
    }

    public function display_box(): void
    {
        $settings  = $this->get_form_settings();
        $row       = JM_TC_DIR_VIEWS_METABOX . "row.php";

        foreach ($settings as $meta) {
            $method = array_shift($meta);
            $view   = JM_TC_DIR_VIEWS_METABOX . "$method.php";

            if (!is_file($view) || !file_exists($view)) {
                continue;
            }

            if ($method === "wrapper") {
                require $view;
            } else {
                require $row;
            }
        }

        wp_nonce_field('save_tc_meta', 'save_tc_meta_nonce');
    }

    public function save_box($post_id, $post): int
    {
        if (!$this->is_post_type_allowed($post->post_type)) {
            return $post_id;
        }

        if (!empty($_POST['save_tc_meta_nonce']) && check_admin_referer('save_tc_meta', 'save_tc_meta_nonce')) {
            foreach ($this->get_postmeta_keys() as $key) {
                if (!empty($_POST[$key])) {
                    update_post_meta($post_id, $key, $this->sanitize($key, $_POST[$key]));
                } else {
                    delete_post_meta($post_id, $key);
                }
            }
        }

        return $post_id;
    }

    private function sanitize($key, $value)
    {
        switch ($key) {
            case 'twitterCardType':
                return in_array($value, [
                    'summary',
                    'summary_large_image',
                    'player',
                    'app',
                ]) ? $value : $this->tc_opts['twitterCardType'];
                break;
            case 'cardImageAlt':
                return esc_textarea($value);
                break;
            case 'cardImage':
            case 'cardPlayer':
            case 'cardPlayerStream':
                return esc_url_raw($value);
                break;
            case 'cardPlayerWidth':
            case 'cardPlayerHeight':
                return intval($value);
                break;
            case 'cardPlayerCodec':
                return sanitize_text_field($value);
                break;
            default:
                return false;
        }
    }

    public function admin_enqueue_scripts(): void
    {

        if (!$this->is_post_type_allowed()) {
            return;
        }

        wp_register_script('jm-tc-metabox', JM_TC_URL . 'admin/js/metabox' . $this->get_assets_suffix() . '.js', ['jquery', 'count-chars'], JM_TC_VERSION, true);

        wp_enqueue_media();
        wp_enqueue_script('jm-tc-metabox');

        wp_localize_script(
            'jm-tc-metabox',
            'tcStrings',
            [
                'upload_message' => esc_html__('Upload', 'jm-tc'),
                'default_image'  => !empty($this->tc_opts['twitterImage']) ? esc_url($this->tc_opts['twitterImage']) : JM_TC_URL . 'assets/img/Twitter_logo_blue.png',
            ]
        );
    }
}
