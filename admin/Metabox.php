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
        $this->tc_opts = jm_tc_get_options();
    }

    /**
     * @see https://github.com/WordPress/gutenberg/blob/master/docs/extensibility/meta-box.md
     */
    public function add_box(): void
    {
        add_meta_box(
            'jm_tc_metabox',
            esc_html__('Twitter Cards', 'jm-tc'),
            [$this, 'display_box'],
            jm_tc_get_post_types()
        );
    }

    public function display_box(): void
    {

        $metaBox = [];

        ob_start();
        require JM_TC_DIR_VIEWS_SETTINGS . "settings-metabox.php";
        ob_get_clean();

        (new Fields)->generate_fields($metaBox);

        wp_nonce_field('save_tc_meta', 'save_tc_meta_nonce');
    }

    /**
     * The save part
     *
     * @param int $post_id The post ID.
     * @param \WP_Post $post The post object.
     * @return int
     */
    public function save_box($post_id, $post): int
    {

        $types = jm_tc_get_post_types();

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

        wp_register_script('jm-tc-metabox', JM_TC_URL . 'admin/js/metabox' . jm_tc_get_assets_suffix() . '.js', ['jquery'], JM_TC_VERSION, true);

        if (in_array(get_post_type(), jm_tc_get_post_types())) {
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
