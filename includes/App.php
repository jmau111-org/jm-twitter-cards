<?php

namespace JMTC;

use JMTC\Admin\Admin;
use JMTC\Admin\Gutenberg;
use JMTC\Admin\Metabox;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!defined('JM_TC_SLUG_MAIN_OPTION')) {
    define('JM_TC_SLUG_MAIN_OPTION', 'jm_tc');
}

if (!defined('JM_TC_SLUG_CPT_OPTION')) {
    define('JM_TC_SLUG_CPT_OPTION', 'jm_tc_cpt');
}

class App
{
    private $opts;

    use Functions;

    public function __construct() {
        $this->opts = $this->get_options();
    }

    public function run()
    {
        $this->load_dependencies();

        if (!$this->gutenberg_exists()) {
            $plugin_posts = new Metabox();
            add_action('add_meta_boxes', [$plugin_posts, 'add_box']);
            add_action('save_post', [$plugin_posts, 'save_box'], 10, 2);
            add_action('admin_enqueue_scripts', [$plugin_posts, 'admin_enqueue_scripts']);
        } else {
            $plugin_posts = new Meta();
            add_action('init', [$plugin_posts, 'gutenberg_register_meta']);

            if (in_array($this->get_current_post_type(), $this->get_post_types(), true)) {
                $gut = new Gutenberg();
                add_action('init', [$gut, 'register_scripts']);
                add_action('enqueue_block_editor_assets', [$gut, 'enqueue_scripts']);
            }
        }

        $plugin_admin = new Admin();
        add_action('admin_enqueue_scripts', [$plugin_admin, 'admin_enqueue_scripts']);
        add_filter('plugin_action_links_' . JM_TC_BASENAME, [$plugin_admin, 'settings_action_link']);
        add_action('admin_menu', [$plugin_admin, 'admin_menu']);
        add_action('admin_init', [$plugin_admin, 'admin_init']);
        add_action('admin_init', [$plugin_admin, 'process_settings_export']);
        add_action('admin_init', [$plugin_admin, 'process_settings_import']);

        $plugin_front = new Front();
        add_action('wp_head', [$plugin_front, 'add_markup'], 0);

        $plugin_front = new Particular();
        add_filter('robots_txt', [$plugin_front, 'robots_mod']);

        if (isset($this->opts['twitterCardExcerpt']) && 'yes' === $this->opts['twitterCardExcerpt']) {
            add_filter('jm_tc_get_excerpt', [$plugin_front, 'modify_excerpt']);
        }

        add_action('wpmu_new_blog', [$plugin_front, 'new_blog']);
        add_filter('jm_tc_card_site', [$plugin_front, 'remover']);
        add_filter('jm_tc_card_creator', [$plugin_front, 'remover']);
    }
    
    private function load_dependencies()
    {
        $dependencies =  [
            'includes/Init',
        ];

        if ($this->gutenberg_exists()) {
            $dependencies = array_merge($dependencies, ['admin/Gutenberg',]);
        } else {
            $dependencies = array_merge($dependencies, ['admin/Metabox',]);
        }

        $dependencies = array_merge($dependencies, [
            'admin/Settings',
            'admin/Admin',
            'admin/Meta',
            'public/Options',
            'public/Front',
            'public/Particular'
        ]);

        foreach ($dependencies as $rel_path) {
            require JM_TC_DIR . "$rel_path.php";
        }
    }
}
