<?php

namespace JMTC;

use JMTC\Admin\Admin;
use JMTC\Admin\Gutenberg;
use JMTC\Admin\Metabox;
use JMTC\Admin\MetaKeys;
use JMTC\Admin\MetaValues;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class App
{
    private $tc_opts;

    use Functions;

    public function __construct() 
    {
        $this->tc_opts = $this->get_options();
    }

    public function hook(): self
    {

        // Main App
        if (!$this->gutenberg_exists()) {
            $metabox = new MetaBox;
            add_action('add_meta_boxes', [$metabox, 'add_box']);
            add_action('save_post', [$metabox, 'save_box'], 10, 2);
            add_action('admin_enqueue_scripts', [$metabox, 'admin_enqueue_scripts']);
        } else {
            $post_meta = new MetaKeys;
            add_action('init', [$post_meta, 'register_meta_keys']);

            if ($this->is_post_type_allowed()) {
                $guten = new Gutenberg;
                add_action('enqueue_block_editor_assets', [$guten, 'enqueue_block_editor_assets']);
            }
        }

        $admin = new Admin;
        add_action('admin_enqueue_scripts', [$admin, 'admin_enqueue_scripts']);
        add_filter('plugin_action_links_' . JM_TC_BASENAME, [$admin, 'settings_action_link']);
        add_action('admin_menu', [$admin, 'admin_menu']);
        add_action('admin_init', [$admin, 'admin_init']);
        add_action('admin_init', [$admin, 'process_settings_export']);
        add_action('admin_init', [$admin, 'process_settings_import']);
        add_action('wpmu_new_blog', [$admin, 'new_blog']);

        // Front App
        add_action('wp_head', [$this, 'render_twitter_cards'], 0);

        // Edge cases
        $particular = new Particular;
        add_filter('robots_txt', [$particular, 'robots_mod']);

        if (isset($this->tc_opts['twitterCardExcerpt']) && 'yes' === $this->tc_opts['twitterCardExcerpt']) {
            add_filter('jm_tc_get_excerpt', [$particular, 'modify_excerpt']);
        }

        add_filter('jm_tc_card_site', [$particular, 'remover']);
        add_filter('jm_tc_card_creator', [$particular, 'remover']);

        return $this;
    }

    /**
     * @since 13
     * it used to be handled by the Front class
     * now the logic is in the App class
     */
    public function render_twitter_cards(): void {

        // we cannot call get_queried_object_id() everywhere
        // it would be too soon before wp_head
        $front = new Front(
            new MetaValues($this->tc_opts, (int) get_queried_object_id())
        );

        if (is_home() || is_front_page()) {
            $front->display_home_meta_tags();
        }

        if (!is_404() 
            && !is_tag() 
            && !is_archive() 
            && !is_tax() 
            && !is_category() 
            && $this->is_post_type_allowed())
        {
            $front->display_meta_tags();
        }
    }

    public function load(): self
    {
        $dependencies = [];

        if ($this->gutenberg_exists()) {
            $dependencies = array_merge($dependencies, ['admin/Gutenberg',]);
        } else {
            $dependencies = array_merge($dependencies, ['admin/MetaBox',]);
        }

        $dependencies = array_merge($dependencies, [
            'admin/Settings',
            'admin/Admin',
            'admin/MetaKeys',
            'admin/MetaValues',
        ]);

        $dependencies = array_merge($dependencies, [
            'public/Front',
            'public/Particular',
        ]);

        foreach ($dependencies as $rel_path) {
            require JM_TC_DIR . "$rel_path.php";
        }
        
        return $this;
    }
}
