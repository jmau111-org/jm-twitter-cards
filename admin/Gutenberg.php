<?php

namespace JMTC\Admin;
use JMTC\Functions;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Gutenberg
{
    use Functions;
    
    public function register_scripts(): void
    {
        if (!is_admin()) {
            return;
        }

        $rel_path_css = JM_TC_GUT_FOLDER . '/style-gutenberg-sidebar.css';
        $rel_path_js = JM_TC_GUT_FOLDER . '/gutenberg-sidebar.js';

        if (!file_exists(JM_TC_DIR . $rel_path_js) || !file_exists(JM_TC_DIR . $rel_path_css)) {
            return;
        }

        wp_register_script(
            'tc-gut-sidebar',
            JM_TC_URL . $rel_path_js,
            [],
            $this->get_asset_version($rel_path_js),
            true
        );

        wp_localize_script(
            'tc-gut-sidebar',
            'tcData',
            [
                'twitterSite'  => $this->remove_at($this->maybe_get_option('twitterSite')),
                'domain'       => get_bloginfo('url'),
                'avatar'       => get_avatar_url(0, 16),
                'defaultImage' => $this->maybe_get_option('twitterImage'),
                'defaultType'  => $this->maybe_get_option('twitterCardType'),
                'pluginUrl'    => JM_TC_URL,
            ]
        );

        /**
         * @see https://developer.wordpress.org/block-editor/developers/internationalization/
         */
        wp_set_script_translations(
            'tc-gut-sidebar',
            'jm-tc-gut',
            plugin_dir_path(dirname(__FILE__)) . "languages"
        );

        wp_register_style(
            'tc-gut-styles',
            JM_TC_URL . $rel_path_css,
            ['wp-edit-blocks'],
            $this->get_asset_version($rel_path_css)
        );
    }

    public function enqueue_scripts(): void
    {
        if (!$this->is_post_type_allowed()) {
            return;
        }

        wp_enqueue_script('tc-gut-sidebar');
        wp_enqueue_style('tc-gut-styles');
    }
}
