<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Admin
{

    private $options;
    private $sub_pages;
    private $settings_api;
    private $video_files;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct()
    {

        $this->sub_pages = apply_filters( "jm_tc_admin_sub_pages", [
            'jm_tc_import_export' => esc_html__('Import', 'jm-tc') . ' / ' . esc_html__('Export', 'jm-tc'),
            'jm_tc_about'         => esc_html__('About', 'jm-tc'),
            'jm_tc_tutorials'     => esc_html__('Tutorials', 'jm-tc'),
        ] );

        $this->video_files = apply_filters( "jm_tc_admin_tuts_vids", [
            '302609444' => esc_html__('Setup for the first time', 'jm-tc'),
            '302609402' => esc_html__('Setup metabox with custom post types', 'jm-tc'),
            '302609437' => esc_html__('Dealing with images', 'jm-tc'),
            '302609425' => esc_html__('Set first image found in post content as twitter image', 'jm-tc'),
            '302609429' => esc_html__('Upgrading to Gutenberg', 'jm-tc'),
            '305338709' => esc_html__('How to recover twitter cards sidebar after unpin', 'jm-tc'),
        ] );
    }

    public function settings_action_link($links): ?array
    {
        $links['settings'] = '<a href="' . add_query_arg(['page' => JM_TC_SLUG_MAIN_OPTION], admin_url('admin.php')) . '">' . esc_html__('Settings', 'jm-tc') . '</a>';

        return $links;
    }

    public function admin_enqueue_scripts($hook_suffix): void
    {

        /**
         * Char count utility
         **************************************************************************************************************/
        $rel_path_js = 'admin/js/charcount' . jm_tc_get_assets_suffix() . '.js';
        wp_register_script(
            'count-chars', 
            JM_TC_URL . $rel_path_js, 
            ['jquery',], 
            jm_tc_get_assets_version($rel_path_js), 
            true
        );
        wp_localize_script(
            'count-chars',
            '_tcStrings',
            [
                'message' => esc_html__('characters left', 'jm-tc'),
            ]
        );

        /**
         * Main page
         **************************************************************************************************************/
        if ('toplevel_page_jm_tc' === $hook_suffix) {
            $rel_path_css = 'admin/css/settings' . jm_tc_get_assets_suffix() . '.css';
            $rel_path_js = 'admin/js/settings' . jm_tc_get_assets_suffix() . '.js';
            wp_enqueue_media();
            wp_enqueue_style('settings', JM_TC_URL . $rel_path_css, [], jm_tc_get_assets_version($rel_path_css));
            wp_enqueue_script('jquery');
            wp_enqueue_script('settings', JM_TC_URL . $rel_path_js, [
                'jquery',
            ], jm_tc_get_assets_version($rel_path_js), true);
            wp_enqueue_script('count-chars');
        }


        /**
         * Import/ page
         **************************************************************************************************************/
        if ('jm-twitter-cards_page_jm_tc_import_export' === $hook_suffix) {
            $rel_path_css = 'admin/css/iexp' . jm_tc_get_assets_suffix() . '.css';
            wp_enqueue_style('iexp', JM_TC_URL . $rel_path_css, [], jm_tc_get_assets_version($rel_path_css));
        }

        /**
         * Tutorials page
         **************************************************************************************************************/
        if ('jm-twitter-cards_page_jm_tc_tutorials' === $hook_suffix) {
            $rel_path_css = 'admin/css/tutorials' . jm_tc_get_assets_suffix() . '.css';
            wp_enqueue_style('tutorials', JM_TC_URL . $rel_path_css, [], jm_tc_get_assets_version($rel_path_css));
        }
    }

    public function admin_init(): void
    {
        load_plugin_textdomain('jm-tc', false, JM_TC_LANG_DIR);
        $this->settings_api = new Settings($this->get_settings_sections(), $this->get_settings_fields());
        $this->settings_api->admin_init();
    }

    public function get_settings_sections(): array
    {
        $sections = [];
        require JM_TC_DIR_VIEWS_SETTINGS . "settings-sections.php";
        return (array) apply_filters("jm_tc_card_settings_sections", $sections);
    }

    public function get_settings_fields(): array
    {
        $settings_fields = [];
        require JM_TC_DIR_VIEWS_SETTINGS . "settings.php";
        return (array) apply_filters("jm_tc_card_settings_fields", $settings_fields);
    }

    public function admin_menu(): void
    {
        add_menu_page(esc_html__('JM Twitter Cards', 'jm-tc'), esc_html__('JM Twitter Cards', 'jm-tc'), 'manage_options', 'jm_tc', [
            $this,
            'plugin_page',
        ], 'dashicons-twitter');

        foreach ($this->sub_pages as $page => $title) {
            add_submenu_page('jm_tc', $title, $title, 'manage_options', $page, [
                $this,
                'get_view',
            ]);
        }
    }

    /**
     * Get our view
     */
    public function get_view(): void
    {

        if (isset($_GET['page']) && in_array($_GET['page'], array_keys($this->sub_pages), true)) {
            // don't allow anything to be loaded
            ob_start();
            $slug = str_replace('jm_tc_', '', sanitize_title_with_dashes($_GET['page']));
            $slug = str_replace('_', '-', $slug);
            require JM_TC_DIR_VIEWS . $slug . ".php";
            ob_end_flush();
        }
    }

    /**
     * Display options
     */
    public function plugin_page(): void
    {
        ob_start();
        $settings = $this->settings_api;
        require JM_TC_DIR_VIEWS_SETTINGS . "settings-plugin-page.php";
        ob_end_flush();
    }

    public function get_post_types($args = []): ?array
    {

        $defaults = ['public' => true,];
        $pt_args  = apply_filters('jm_tc_cpt_args', wp_parse_args($args, $defaults));

        if (!is_array($pt_args)) {
            $pt_args = [];
        }

        return get_post_types($pt_args);
    }

    /**
     * Process a settings export that generates a .json file of the shop settings
     * @since 5.3.2
     */
    public function process_settings_export(): void
    {

        if (empty($_POST['action']) || 'export_settings' !== $_POST['action']) {
            return;
        }

        if (!wp_verify_nonce($_POST['export_nonce'], 'export_nonce')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = [
            'tc' => (array) get_option(JM_TC_SLUG_MAIN_OPTION),
            'ie' => (array) get_option(JM_TC_SLUG_CPT_OPTION),
        ];

        ignore_user_abort(true);

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=jm-twitter-cards-settings-export-' . strtotime('now') . '.json');
        header('Expires: 0');

        echo wp_json_encode($settings);
        exit;
    }

    /**
     * Process a settings import from a json file
     * @since 5.3.2
     */
    public function process_settings_import(): void
    {

        if (empty($_POST['action']) || 'import_settings' !== $_POST['action']) {
            return;
        }

        if (!wp_verify_nonce($_POST['import_nonce'], 'import_nonce')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $extension = end(explode('.', $_FILES['import_file']['name']));

        if ('json' !== $extension) {
            wp_die(esc_html__('Please upload a valid .json file', 'jm-tc'));
        }

        $import_file = $_FILES['import_file']['tmp_name'];

        if (empty($import_file)) {
            wp_die(esc_html__('Please upload a file to import', 'jm-tc'));
        }

        /**
         * array associative
         *
         */
        $settings = (array) json_decode(file_get_contents($import_file), true);

        if (!empty($settings['tc'])) {
            update_option(JM_TC_SLUG_MAIN_OPTION, (array) $settings['tc']);
        }

        if (!empty($settings['ie'])) {
            update_option(JM_TC_SLUG_CPT_OPTION, (array) $settings['ie']);
        }

        wp_safe_redirect(add_query_arg('page', JM_TC_SLUG_MAIN_OPTION, admin_url('admin.php')));
        exit;
    }
}
