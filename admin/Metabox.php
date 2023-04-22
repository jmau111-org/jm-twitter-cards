<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Metabox
{
    private $keys;
    private $tc_opts;

    use Functions;

    public function __construct()
    {
        $this->keys = [
            'twitterCardType',
            'cardImage',
            'cardImageAlt',
            'cardPlayer',
            'cardPlayerWidth',
            'cardPlayerHeight',
            'cardPlayerStream',
            'cardPlayerCodec',
        ];

        $this->tc_opts = $this->get_options();
    }

    public function add_box(): void
    {
        add_meta_box(
            '$this->metabox',
            esc_html__('Twitter Cards', 'jm-tc'),
            [$this, 'display_box'],
            $this->get_post_types()
        );
    }

    public function display_box(): void
    {
        ob_start();
        $rules     = JM_TC_DIR_VIEWS_PARAMS . "metabox.php";
        $row_start = JM_TC_DIR_VIEWS_METABOX . "row-start.php";
        $row_end   = JM_TC_DIR_VIEWS_METABOX . "row-start.php";

        require $rules;

        foreach ($array as $options) {
            $method = array_shift($options);
            $view = JM_TC_DIR_VIEWS_METABOX . "$method.php";

            if (is_file($path) 
                && file_exists($path) 
                && !empty($array["field_id"])) {
                require $row_start;
                require $view;
                require $row_end;
            }
        }
        ob_get_flush();

        wp_nonce_field('save_tc_meta', 'save_tc_meta_nonce');
    }

    public function save_box($post_id, $post): int
    {

        $types = $this->get_post_types();

        if (!in_array($post->post_type, $types)) {
            return $post_id;
        }

        if (!empty($_POST['save_tc_meta_nonce']) && check_admin_referer('save_tc_meta', 'save_tc_meta_nonce')) {
            foreach ((array) $this->keys as $key) {

                if (!empty($_POST[$key])) {
                    update_post_meta($post_id, $key, $this->sanitize($key, $_POST[$key]));
                } elseif (empty($_POST[$key])) {
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
        wp_register_script('jm-tc-metabox', JM_TC_URL . 'admin/js/metabox' . $this->get_assets_suffix() . '.js', ['jquery'], JM_TC_VERSION, true);

        if (in_array(get_post_type(), $this->get_post_types())) {
            wp_enqueue_media();
            wp_enqueue_script('count-chars');
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
}
