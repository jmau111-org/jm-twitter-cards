<?php

namespace JMTC\Admin;
use JMTC\Functions;


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
    private $sub_pages;
    private $settings;

    use Functions;

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
    }

    public function new_blog($blog_id): void
    {
        switch_to_blog($blog_id);
        $this->fill_default_options();
        restore_current_blog();
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
        $rel_path_js = 'admin/js/charcount' . $this->get_assets_suffix() . '.js';
        wp_register_script(
            'count-chars', 
            JM_TC_URL . $rel_path_js, 
            ['jquery',], 
            $this->get_asset_version($rel_path_js), 
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
            $rel_path_css = 'admin/css/settings' . $this->get_assets_suffix() . '.css';
            $rel_path_js = 'admin/js/settings' . $this->get_assets_suffix() . '.js';
            wp_enqueue_media();
            wp_enqueue_style('settings', JM_TC_URL . $rel_path_css, [], $this->get_asset_version($rel_path_css));
            wp_enqueue_script('jquery');
            wp_enqueue_script('settings', JM_TC_URL . $rel_path_js, [
                'jquery',
            ], $this->get_asset_version($rel_path_js), true);
            wp_enqueue_script('count-chars');
        }


        /**
         * Import/ page
         **************************************************************************************************************/
        if ('jm-twitter-cards_page_jm_tc_import_export' === $hook_suffix) {
            $rel_path_css = 'admin/css/iexp' . $this->get_assets_suffix() . '.css';
            wp_enqueue_style('iexp', JM_TC_URL . $rel_path_css, [], $this->get_asset_version($rel_path_css));
        }

        /**
         * Tutorials page
         **************************************************************************************************************/
        if ('jm-twitter-cards_page_jm_tc_tutorials' === $hook_suffix) {
            $rel_path_css = 'admin/css/tutorials' . $this->get_assets_suffix() . '.css';
            wp_enqueue_style('tutorials', JM_TC_URL . $rel_path_css, [], $this->get_asset_version($rel_path_css));
        }
    }

    public function admin_init(): void
    {
        load_plugin_textdomain('jm-tc', false, JM_TC_LANG_DIR);

        $opts = $this->get_options();
        $sections = [
            [
                'id'    => JM_TC_SLUG_MAIN_OPTION,
                'title' => esc_html__('Options', 'jm-tc'),
            ],
            [
                'id'    => JM_TC_SLUG_CPT_OPTION,
                'title' => esc_html__('Custom Post types', 'jm-tc'),
            ],
        ];
        $settings_fields = [
            JM_TC_SLUG_MAIN_OPTION => [
                [
                    'name'  => 'twitterCreator',
                    'label' => esc_html__('Creator (twitter username)', 'jm-tc'),
                    'type'  => 'text',
                ],
                [
                    'name'  => 'twitterSite',
                    'label' => esc_html__('Site (twitter username)', 'jm-tc'),
                    'type'  => 'text',
                ],
                [
                    'name'    => 'twitterCardType',
                    'label'   => esc_html__('Card type', 'jm-tc'),
                    'type'    => 'select',
                    'default' => 'summary',
                    'options' => [
                        'summary'             => esc_html__('Summary', 'jm-tc'),
                        'summary_large_image' => esc_html__('Summary below Large Image', 'jm-tc'),
                        'app'                 => esc_html__('Application', 'jm-tc'),
                    ],
                ],
                [
                    'name'    => 'twitterCardExcerpt',
                    'label'   => esc_html__('Excerpt', 'jm-tc'),
                    'desc'    => esc_html__('Excerpt as meta desc?', 'jm-tc'),
                    'type'    => 'radio',
                    'default' => 'no',
                    'options' => [
                        'yes' => esc_html__('yes', 'jm-tc'),
                        'no'  => esc_html__('no', 'jm-tc'),
                    ],
                ],
                [
                    'name'    => 'twitterCardOg',
                    'label'   => esc_html__('Open Graph', 'jm-tc'),
                    'desc'    => esc_html__('Open Graph/SEO', 'jm-tc'),
                    'type'    => 'radio',
                    'default' => 'no',
                    'options' => [
                        'no'  => esc_html__('no', 'jm-tc'),
                        'yes' => esc_html__('yes', 'jm-tc'),
                    ],
                ],
                [
                    'name'    => 'twitterImage',
                    'label'   => esc_html__('Image Fallback', 'jm-tc'),
                    'type'    => 'file',
                    'default' => $opts["twitterImage"],
                ],
                [
                    'name'      => 'twitterImageAlt',
                    'label'     => esc_html__('Image alt', 'jm-tc'),
                    'type'      => 'textarea',
                    'charcount' => 420,
                ],
                [
                    'label'     => esc_html__('Home meta desc', 'jm-tc'),
                    'desc'      => esc_html__('Enter desc for Posts Page (max: 200 characters)', 'jm-tc'),
                    'name'      => 'twitterPostPageDesc',
                    'type'      => 'textarea',
                    'charcount' => 200,
                ],
                [
                    'label' => esc_html__('iPhone Name', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPhone Name ', 'jm-tc'),
                    'name'  => 'twitteriPhoneName',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__(' iPhone URL', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPhone URL ', 'jm-tc'),
                    'name'  => 'twitteriPhoneUrl',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('iPhone ID', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPhone ID ', 'jm-tc'),
                    'name'  => 'twitteriPhoneId',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('iPad Name', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPad Name ', 'jm-tc'),
                    'name'  => 'twitteriPadName',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('iPad URL', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPad URL ', 'jm-tc'),
                    'name'  => 'twitteriPadUrl',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('iPad ID', 'jm-tc'),
                    'desc'  => esc_html__('Enter iPad ID ', 'jm-tc'),
                    'name'  => 'twitteriPadId',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('Google Play Name', 'jm-tc'),
                    'desc'  => esc_html__('Enter Google Play Name ', 'jm-tc'),
                    'name'  => 'twitterGooglePlayName',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('Google Play URL', 'jm-tc'),
                    'desc'  => esc_html__('Enter Google Play URL ', 'jm-tc'),
                    'name'  => 'twitterGooglePlayUrl',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('Google Play ID', 'jm-tc'),
                    'desc'  => esc_html__('Enter Google Play ID ', 'jm-tc'),
                    'name'  => 'twitterGooglePlayId',
                    'type'  => 'text',
                ],
                [
                    'label' => esc_html__('App Country code', 'jm-tc'),
                    'desc'  => esc_html__('Enter 2 letter App Country code in case your app is not available in the US app store', 'jm-tc'),
                    'name'  => 'twitterAppCountry',
                    'type'  => 'text',
                ],
            ],
            JM_TC_SLUG_CPT_OPTION  => [
                [
                    'name'    => 'twitterCardPt',
                    'label'   => esc_html__('Add or hide the meta box', 'jm-tc'),
                    'desc'    => esc_html__('Default', 'jm-tc') . ': ' . esc_html__('All', 'jm-tc'),
                    'type'    => 'multicheck',
                    'options' => $this->get_post_types(),
                ],
            ],
        ];

        $this->settings = new Settings(
            (array) apply_filters("jm_tc_card_settings_sections", $sections), 
            (array) apply_filters("jm_tc_card_settings_fields", $settings_fields)
        );

        $this->settings->admin_init();
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
                'get_admin_view',
            ]);
        }
    }

    public function get_admin_view(): void
    {
        if (isset($_GET['page']) && in_array($_GET['page'], array_keys($this->sub_pages), true)) {
            ob_start();
            $slug = str_replace('jm_tc_', '', sanitize_title_with_dashes($_GET['page']));
            $slug = str_replace('_', '-', $slug);
            require JM_TC_DIR_VIEWS . $slug . ".php";
            ob_end_flush();
        }
    }

    public function plugin_page(): void
    {
        ob_start();
        $sections = $this->settings->get_settings_sections();
        $count = count($sections);
        require JM_TC_DIR_VIEWS_SETTINGS . "general.php";
        ob_end_flush();
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
