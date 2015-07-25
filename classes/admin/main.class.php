<?php
namespace TokenToMe\TwitterCards\Admin;

if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}


class Main
{
    /**
     * Option key, and option page slug
     * @var string
     */
    protected static $key = 'jm_tc';

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected static $plugin_options = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct()
    {

        $this->title = __('JM Twitter Cards', JM_TC_TEXTDOMAIN);
        add_action('admin_init', array($this, 'mninit'));
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_filter('cmb_frontend_form_format', array($this, 'save_button'), 10, 3);
        add_action('cmb_save_options-page_fields', array($this, 'is_it_saved'));
    }

    /**
     * @return array
     */
    public static function option_fields()
    {

        // Only need to initiate the array once per page-load
        if (!empty(self::$plugin_options)) {
            return self::$plugin_options;
        }

        self::$plugin_options = array(
            'id' => self::$key,
            'show_on' => array('key' => 'options-page', 'value' => array(self::$key,),),
            'show_names' => true,
            'fields' => array(

                array(
                    'name' => __('Creator (twitter username)', JM_TC_TEXTDOMAIN),
                    'desc' => __('Who is the creator of content?', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCreator',
                    'type' => 'text_medium',
                ),
                array(
                    'name' => __('Site (twitter username)', JM_TC_TEXTDOMAIN),
                    'desc' => __('Who is the Owner of the Website? (could be a trademark)', 'jm-tc'),
                    'id' => 'twitterSite',
                    'type' => 'text_medium',
                ),
                array(
                    'name' => __('Card Types', JM_TC_TEXTDOMAIN),
                    'desc' => __('Choose what type of card you want to use', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardType',
                    'type' => 'select',
                    'options' => array(
                        'summary' => __('Summary', JM_TC_TEXTDOMAIN),
                        'summary_large_image' => __('Summary below Large Image', JM_TC_TEXTDOMAIN),
                        'photo' => __('Photo', JM_TC_TEXTDOMAIN),
                        'app' => __('Application', JM_TC_TEXTDOMAIN),
                    )
                ),
                array(
                    'name' => __('Add or hide the meta box', JM_TC_TEXTDOMAIN),
                    'desc' => __('Hide or display the meta box on post edit. This will display/hide both image and main metaboxes.', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardMetabox',
                    'type' => 'select',
                    'options' => array(
                        'yes' => __('Display', JM_TC_TEXTDOMAIN),
                        'no' => __('Hide', JM_TC_TEXTDOMAIN),
                    ),
                ),
                array(
                    'name' => __('Open Graph', JM_TC_TEXTDOMAIN),
                    'desc' => __('Open Graph/SEO', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardOg',
                    'type' => 'select',
                    'options' => array(
                        'no' => __('no', JM_TC_TEXTDOMAIN),
                        'yes' => __('yes', JM_TC_TEXTDOMAIN),
                    )
                ),
                array(
                    'name' => __('Excerpt'),
                    'desc' => __('Excerpt as meta desc?', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardExcerpt',
                    'type' => 'select',
                    'options' => array(
                        'no' => __('no', JM_TC_TEXTDOMAIN),
                        'yes' => __('yes', JM_TC_TEXTDOMAIN),
                    )
                ),
                array(
                    'name' => __('Twitter\'s bot', JM_TC_TEXTDOMAIN),
                    'desc' => __('Add required rules in robots.txt', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardRobotsTxt',
                    'type' => 'select',
                    'options' => array(
                        'yes' => __('Yes', JM_TC_TEXTDOMAIN),
                        'no' => __('No', JM_TC_TEXTDOMAIN),
                    )
                ),

            )
        );


        return self::$plugin_options;
    }


    /**
     * Make public the protected $key variable.
     * @since  0.1.0
     * @return string  Option key
     */
    public static function key()
    {
        return self::$key;
    }


    /**
     * Alter save_button
     * @since  0.1.0
     *
     * @param $object_id
     * @param $meta_box
     * @param $form
     *
     * @return string
     */
    public function save_button($object_id, $meta_box, $form)
    {

        $form = '<form class="cmb-form" method="post" id="%s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%s">%s<input type="submit" name="submit-cmb" value="%s" class="button button-primary"></form>';

        return $form;
    }

    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function mninit()
    {
        register_setting(self::$key, self::$key);
    }

    /**
     * Add subpages to admin
     * @since  5.3.0
     */
    public function subpages()
    {
        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                case 'jm_tc':
                    require(JM_TC_DIR . 'views/pages/main.php');
                    break;
                case 'jm_tc_cf':
                    require(JM_TC_DIR . 'views/pages/custom_fields.php');
                    break;
                case 'jm_tc_import_export':
                    require(JM_TC_DIR . 'views/pages/import-export.php');
                    break;

                case 'jm_tc_images':
                    require(JM_TC_DIR . 'views/pages/images.php');
                    break;

                case 'jm_tc_multi_author':
                    require(JM_TC_DIR . 'views/pages/multi_author.php');
                    break;

                case 'jm_tc_home':
                    require(JM_TC_DIR . 'views/pages/home.php');
                    break;

                case 'jm_tc_deep_linking':
                    require(JM_TC_DIR . 'views/pages/deep_linking.php');
                    break;

                case 'jm_tc_doc':
                    require(JM_TC_DIR . 'views/pages/documentation.php');
                    break;

                case 'jm_tc_about':
                    require(JM_TC_DIR . 'views/pages/about.php');
                    break;

                case 'jm_tc_tutorial':
                    require(JM_TC_DIR . 'views/pages/tutorial.php');
                    break;
            }
        }
    }

    /**
     * Add menu options page
     * @since 5.3.0
     */
    public function add_page()
    {

        $this->options_page = add_menu_page($this->title, $this->title, 'manage_options', self::$key, array(
            $this,
            'subpages',
        ), 'dashicons-twitter');
        $this->options_page_options = add_submenu_page('jm_tc', __('General'), __('General'), 'manage_options', self::$key, array(
            $this,
            'subpages',
        ));

        $this->options_page_import_export = add_submenu_page('jm_tc', __('Import') . ' / ' . __('Export'), __('Import') . ' / ' . __('Export'), 'manage_options', 'jm_tc_import_export', array(
            $this,
            'subpages',
        ));
        $this->options_subpage_tutorial = add_submenu_page('jm_tc', __('Tutorial'), __('Tutorial'), 'manage_options', 'jm_tc_tutorial', array(
            $this,
            'subpages',
        ));

        $this->options_subpage_images = add_submenu_page('jm_tc', __('Images', JM_TC_TEXTDOMAIN), __('Images', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_images', array(
            $this,
            'subpages',
        ));

        $this->options_subpage_cf = add_submenu_page('jm_tc', __('Custom fields', JM_TC_TEXTDOMAIN), __('Custom fields', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_cf', array(
            $this,
            'subpages',
        ));

        $this->options_subpage_home = add_submenu_page('jm_tc', __('Home settings', JM_TC_TEXTDOMAIN), __('Home settings', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_home', array(
            $this,
            'subpages',
        ));

        //there is no point displaying this option page is the website is not multi_author !
        if (is_multi_author()) {
            $this->options_subpage_multi_author = add_submenu_page('jm_tc', __('Multi Author', JM_TC_TEXTDOMAIN), __('Multi Author', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_multi_author', array(
                $this,
                'subpages',
            ));
        }

        $this->options_subpage_deep_linking = add_submenu_page('jm_tc', __('Deep Linking', JM_TC_TEXTDOMAIN), __('Deep Linking', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_deep_linking', array(
            $this,
            'subpages',
        ));
        $this->options_subpage_doc = add_submenu_page('jm_tc', __('Documentation', JM_TC_TEXTDOMAIN), __('Documentation', JM_TC_TEXTDOMAIN), 'manage_options', 'jm_tc_doc', array(
            $this,
            'subpages',
        ));

        $this->options_subpage_about = add_submenu_page('jm_tc', __('About'), __('About'), 'manage_options', 'jm_tc_about', array(
            $this,
            'subpages',
        ));

    }

    /**
     * Enqueue scripts conditionnally in admin
     * @since  5.3
     *
     * @param $hook_suffix
     */
    public function admin_scripts($hook_suffix)
    {

        switch ($hook_suffix) {

            case 'toplevel_page_jm_tc':
            case 'jm-twitter-cards_page_jm_tc_import_export':
            case 'jm-twitter-cards_page_jm_tc_tutorial':
            case 'jm-twitter-cards_page_jm_tc_about':
            case 'jm-twitter-cards_page_jm_tc_cf':
            case 'jm-twitter-cards_page_jm_tc_images':
            case 'jm-twitter-cards_page_jm_tc_multi_author':
            case 'jm-twitter-cards_page_jm_tc_deep_linking':

                wp_enqueue_style('jm-tc-admin-style', JM_TC_CSS_URL . 'jm-tc-admin.css');

                break;

            case 'post.php':
            case 'post-new.php':

                wp_enqueue_style('jm-tc-metabox', JM_TC_CSS_URL . 'jm-tc-meta-box.css');
                wp_enqueue_script('jm-tc-metabox', JM_TC_JS_URL . 'jm-tc-meta-box.js', array('jquery'), null, false);

                break;

            case 'jm-twitter-cards_page_jm_tc_home':

                wp_enqueue_style('jm-tc-admin-style', JM_TC_CSS_URL . 'jm-tc-admin.css');
                wp_enqueue_script('jm-tc-admin-script', JM_TC_JS_URL . 'jm-tc-admin-home.js'
                    , array('jquery')
                    , '1.0'
                    , true
                );

                break;

        }

    }

    /**
     * Displays confirmation mess for saving settings
     * @since  5.3.0
     */
    public function is_it_saved()
    {
        ?>
        <div id="message" class="updated">
            <p><strong><?php esc_html_e('Settings saved.'); ?></strong></p>
        </div>
        <?php
    }
}

