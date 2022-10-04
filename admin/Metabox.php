<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utils as Utilities;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Metabox
{

    protected $keys;
    protected $opts;
    protected $fields;

    function __construct()
    {

        $this->fields = new Fields();

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
        $this->opts = \jm_tc_get_options();
    }

    /**
     * register box
     *
     * @see https://github.com/WordPress/gutenberg/blob/master/docs/extensibility/meta-box.md
     * @author jmau111
     * @return mixed|void
     */
    public function add_box()
    {
        add_meta_box(
            'jm_tc_metabox',
            esc_html__('Twitter Cards', 'jm-tc'),
            [$this, 'display_box'],
            Utilities::get_post_types()
        );
    }

    /**
     * callback - box
     *
     * @author jmau111
     */
    public function display_box()
    {

        $metaBox = [];

        ob_start();
        require JM_TC_DIR_VIEWS_SETTINGS . "settings-metabox.php";
        ob_get_clean();

        $this->fields->generate_fields($metaBox);

        wp_nonce_field('save_tc_meta', 'save_tc_meta_nonce');
    }

    /**
     * @author jmau111
     * @return int
     */
    public static function get_post_id()
    {
        return isset($_GET['post']) ? absint($_GET['post']) : 0;
    }

    /**
     * The save part
     *
     * @param int $post_id The post ID.
     * @param \WP_Post $post The post object.
     *
     * @author jmau111
     *
     * @return int
     */
    public function save_box($post_id, $post)
    {

        $types = Utilities::get_post_types();

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

    /**
     * Validate values
     *
     * @param $key
     * @param $value
     *
     * @author jmau111
     * @return int|bool|string
     */
    public function sanitize($key, $value)
    {

        switch ($key) {
            case 'twitterCardType':
                return in_array($value, [
                    'summary',
                    'summary_large_image',
                    'player',
                    'app',
                ]) ? $value : $this->opts['twitterCardType'];
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

    /**
     * Add some js
     * for metabox
     * no need to show all fields if not player
     *
     * @author jmau111
     */
    public function admin_enqueue_scripts()
    {

        wp_register_script('jm-tc-metabox', JM_TC_URL . 'admin/js/metabox' . Utilities::assets_suffix() . '.js', ['jquery'], JM_TC_VERSION, true);

        if (in_array(get_post_type(), Utilities::get_post_types())) {
            wp_enqueue_media();
            wp_enqueue_script('count-chars');
            wp_enqueue_script('jm-tc-metabox');

            wp_localize_script(
                'jm-tc-metabox',
                'tcStrings',
                [
                    'upload_message' => esc_html__('Upload', 'jm-tc'),
                    'default_image'  => !empty($this->opts['twitterImage']) ? esc_url($this->opts['twitterImage']) : JM_TC_URL . 'assets/img/Twitter_logo_blue.png',
                ]
            );
        }
    }
}
