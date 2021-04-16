<?php

namespace TokenToMe\TwitterCards;

use TokenToMe\TwitterCards\Admin\Admin;
use TokenToMe\TwitterCards\Admin\Gutenberg;
use TokenToMe\TwitterCards\Admin\Metabox;

if (!defined('JM_TC_SLUG_MAIN_OPTION')) {
    define('JM_TC_SLUG_MAIN_OPTION', 'jm_tc');
}

if (!defined('JM_TC_SLUG_CPT_OPTION')) {
    define('JM_TC_SLUG_CPT_OPTION', 'jm_tc_cpt');
}

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class App
{

    protected $opts;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function run()
    {

        $this->load_dependencies();
        $this->define_post_hooks();

        if (is_admin()) { // I want this
            $this->define_admin_hooks();
        } else {
            $this->define_public_hooks();
        }

        $this->opts = \jm_tc_get_options();
    }

    /**
     * @author unknown
     */
    protected function load_dependencies()
    {

        require JM_TC_DIR . 'includes/Init.php';
        require JM_TC_DIR . 'includes/Utils.php';
        require JM_TC_DIR . 'includes/Functions.php';

        if (Utils::gutenberg_exists()) {
            require JM_TC_DIR . 'admin/Gutenberg.php';
        } else {
            require JM_TC_DIR . 'admin/Fields.php';
            require JM_TC_DIR . 'admin/Metabox.php';
        }

        require JM_TC_DIR . 'admin/Admin.php';
        require JM_TC_DIR . 'admin/Settings.php';
        require JM_TC_DIR . 'admin/Options.php';
        require JM_TC_DIR . 'admin/Meta.php';
        require JM_TC_DIR . 'public/Front.php';
        require JM_TC_DIR . 'public/Particular.php';
    }


    /**
     * Register all of the hooks related to the map post type
     * of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     */
    protected function define_post_hooks()
    {
        if (!Utils::gutenberg_exists()) {
            $plugin_posts = new Metabox();
            add_action('add_meta_boxes', [$plugin_posts, 'add_box']);
            add_action('save_post', [$plugin_posts, 'save_box'], 10, 2);
            add_action('admin_enqueue_scripts', [$plugin_posts, 'admin_enqueue_scripts']);
        } else {
            $plugin_posts = new Meta();
            add_action('init', [$plugin_posts, 'gutenberg_register_meta']);
        }
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     */
    protected function define_public_hooks()
    {
        $plugin_front = new Front();
        add_action('wp_head', [$plugin_front, 'add_markup'], 0);

        $plugin_front = new Particular();
        add_filter('robots_txt', [$plugin_front, 'robots_mod']);

        if (isset($this->opts['twitterCardExcerpt']) && 'yes' === $this->opts['twitterCardExcerpt']) {
            add_filter('jm_tc_get_excerpt', [$plugin_front, 'modify_excerpt']);
        }

        add_action('wpmu_new_blog', [$plugin_front, 'new_blog']);
        add_filter('jm_tc_card_site', [$plugin_front, 'remove_myself']);
        add_filter('jm_tc_card_creator', [$plugin_front, 'remove_myself']);
    }

    /**
     * Register all of the hooks related to the admin functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     */
    protected function define_admin_hooks()
    {

        $plugin_admin = new Admin();
        add_action('admin_enqueue_scripts', [$plugin_admin, 'admin_enqueue_scripts']);
        add_filter('plugin_action_links_' . JM_TC_BASENAME, [$plugin_admin, 'settings_action_link']);
        add_action('admin_menu', [$plugin_admin, 'admin_menu']);
        add_action('admin_init', [$plugin_admin, 'admin_init']);
        add_action('admin_init', [$plugin_admin, 'process_settings_export']);
        add_action('admin_init', [$plugin_admin, 'process_settings_import']);

        $post_type = get_post_type();

        if (!isset($_GET['post_type'])) {
            $post_type = 'post';
        } elseif (in_array($_GET['post_type'], get_post_types(['show_ui' => true]))) {
            $post_type = $_GET['post_type'];
        }

        if (Utils::gutenberg_exists() && in_array($post_type, Utils::get_post_types(), true)) {
            $gut = new Gutenberg();
            add_action('init', [$gut, 'register_scripts']);
            add_action('enqueue_block_editor_assets', [$gut, 'enqueue_scripts']);
        }
    }
}
