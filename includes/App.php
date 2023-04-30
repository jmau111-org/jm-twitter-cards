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

class App
{
    private $opts;

    use Functions;

    public function __construct() {
        $this->opts = $this->get_options();
    }

    public function hook(): self
    {
        if (!$this->gutenberg_exists()) {
            $metabox = new Metabox;
            add_action('add_meta_boxes', [$metabox, 'add_box']);
            add_action('save_post', [$metabox, 'save_box'], 10, 2);
            add_action('admin_enqueue_scripts', [$metabox, 'admin_enqueue_scripts']);
        } else {
            $post_meta = new Meta;
            add_action('init', [$post_meta, 'gutenberg_register_meta']);

            if (in_array($this->get_current_post_type(), $this->get_post_types(), true)) {
                $guten = new Gutenberg;
                add_action('init', [$guten, 'register_scripts']);
                add_action('enqueue_block_editor_assets', [$guten, 'enqueue_scripts']);
            }
        }

        $admin = new Admin;
        add_action('admin_enqueue_scripts', [$admin, 'admin_enqueue_scripts']);
        add_filter('plugin_action_links_' . JM_TC_BASENAME, [$admin, 'settings_action_link']);
        add_action('admin_menu', [$admin, 'admin_menu']);
        add_action('admin_init', [$admin, 'admin_init']);
        add_action('admin_init', [$admin, 'process_settings_export']);
        add_action('admin_init', [$admin, 'process_settings_import']);

        $front = new Front;
        add_action('wp_head', [$front, 'add_markup'], 0);

        $particular = new Particular;
        add_filter('robots_txt', [$particular, 'robots_mod']);

        if (isset($this->opts['twitterCardExcerpt']) && 'yes' === $this->opts['twitterCardExcerpt']) {
            add_filter('jm_tc_get_excerpt', [$particular, 'modify_excerpt']);
        }

        add_action('wpmu_new_blog', [$particular, 'new_blog']);
        add_filter('jm_tc_card_site', [$particular, 'remover']);
        add_filter('jm_tc_card_creator', [$particular, 'remover']);

        return $this;
    }

    public function load(): self
    {
        $dependencies = [];

        if ($this->gutenberg_exists()) {
            $dependencies = array_merge($dependencies, ['admin/Gutenberg',]);
        } else {
            $dependencies = array_merge($dependencies, ['admin/Metabox',]);
        }

        $dependencies = array_merge($dependencies, [
            'admin/Settings',
            'admin/Admin',
            'admin/Meta',
        ]);

        $dependencies = array_merge($dependencies, [
            'public/Options',
            'public/Front',
            'public/Particular',
        ]);

        foreach ($dependencies as $rel_path) {
            require JM_TC_DIR . "$rel_path.php";
        }
        
        return $this;
    }
}
