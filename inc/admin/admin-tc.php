<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'JM_TC_Admin' ) ) {
	class JM_TC_Admin{
		
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
		public function __construct() {
			// Set our title
			$this->title = __( 'JM Twitter Cards', 'jm-tc');
			add_action( 'admin_init', array( $this, 'mninit' ) );
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			//add_action( 'admin_enqueue_scripts',  array( $this, 'meta_box_scripts' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_styles' ) );
		}
		
		/**
		* Register our setting to WP
		* @since  0.1.0
		*/
		public function mninit() {
			register_setting( static::$key, static::$key );
		}
		
		/**
		*
		*  Links to doc
		*/
		public static function docu_links($n = 0){
			$anchor = array(
			'#general',
			'#metabox',
			'#pagehome',
			'#seo',
			'#images',
			'#deeplinking',
			'#analytics',
			'#faq-crawl'

			);
			$docu  = '<a class="button button-secondary docu" target="_blank" href="' . esc_url(admin_url().'admin.php?page=jm_tc_doc') . $anchor[$n] . '">' . __('Documentation', 'jm-tc') . '</a>';
			$docu .= '&nbsp;<a class="button button-secondary docu" target="_blank" href="' . esc_url('https://dev.twitter.com/docs/cards/validation/validator') . '">' . __('Validator', 'jm-tc') . '</a>';
			$docu .= '&nbsp;<a class="button button-secondary docu" target="_blank" href="' . esc_url('https://dev.twitter.com/docs/cards/troubleshooting') . '">' . __('Troubleshooting', 'jm-tc') . '</a>';
			
			return $docu;
		}

		
		
		/**
		* Add menu options page
		* @since 0.1.0
		*/
		public function add_page() {
		
			$this->options_page 					= add_menu_page( $this->title, $this->title, 'manage_options', static::$key, array( $this, 'admin_page_display' ), JM_TC_URL.'img/bird_blue_16.png', PHP_INT_MAX);
			$this->options_page_options 			= add_submenu_page( 'jm_tc', __('General'), 'General', 'manage_options', static::$key, array( $this, 'admin_page_display' ) );
			$this->options_subpage_images 			= add_submenu_page( 'jm_tc', __( 'Images', 'jm-tc' ), 'Images' , 'manage_options', 'jm_tc_images', 'jm_tc_subpages' );
			$this->options_subpage_seo 				= add_submenu_page( 'jm_tc', __( 'SEO' ), 'SEO' , 'manage_options', 'jm_tc_seo', 'jm_tc_subpages' );
			$this->options_subpage_robots 			= add_submenu_page( 'jm_tc', __( 'robots.txt' ), 'robots.txt' , 'manage_options', 'jm_tc_robots', 'jm_tc_subpages' );
			$this->options_subpage_home 			= add_submenu_page( 'jm_tc', __( 'Home settings' ), 'Home settings' , 'manage_options', 'jm_tc_home', 'jm_tc_subpages' );
			
			//there is no point displaying this option page is the website is not multi_author !
			if ( is_multi_author() ) 
				$this->options_subpage_multi_author 	= add_submenu_page( 'jm_tc', __( 'Multi Author' ), __( 'Multi Author') , 'manage_options', 'jm_tc_multi_author', 'jm_tc_subpages' );
			
			$this->options_subpage_seo 				= add_submenu_page( 'jm_tc', __( 'Deep Linking' ), 'Deep Linking' , 'manage_options', 'jm_tc_deep_linking', 'jm_tc_subpages' );
			$this->options_subpage_doc 				= add_submenu_page( 'jm_tc', __( 'Documentation', 'jm-tc' ), 'Documentation' , 'manage_options', 'jm_tc_doc', 'jm_tc_subpages' );
			$this->options_subpage_analytics 		= add_submenu_page( 'jm_tc', __( 'Analytics', 'jm-tc' ), 'Analytics' , 'manage_options', 'jm_tc_analytics', 'jm_tc_subpages' );		
			$this->options_subpage_about 			= add_submenu_page( 'jm_tc', __( 'About' ), 'About' , 'manage_options', 'jm_tc_about', 'jm_tc_subpages' );
			
			add_action( 'load-' . $this->options_subpage_home, array( $this, 'load_admin_page_home_scripts' ) );
			add_action( 'load-' . $this->options_subpage_doc, array( $this, 'load_admin_doc_scripts' ) );
		}
		

		// I prefer this way even if it's not so good^^
		public function load_admin_page_home_scripts()
		{
			wp_enqueue_script('jm-tc-admin-script', JM_TC_JS_URL.'jm-tc-admin-home.js'
			, array('jquery') 
			, '1.0'
			, true
			);
		}
		
		public function admin_styles()
		{
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array('jm_tc', 'jm_tc_about', 'jm_tc_seo', 'jm_tc_images', 'jm_tc_multi_author', 'jm_tc_home', 'jm_tc_robots', 'jm_tc_deep_linking', 'jm_tc_analytics') ) ) 
			{
				wp_enqueue_style('jm-tc-admin-style', JM_TC_CSS_URL.'jm-tc-admin.css');
			}
		}
		
		
		public function load_admin_doc_scripts()
		{
			
			load_plugin_textdomain('jm-tc-doc', false, JM_TC_LANG_DIR);
			wp_enqueue_style('jm-tc-doc-style', JM_TC_CSS_URL.'jm-tc-documentation.css');
		}
		
		/*	
			public function meta_box_scripts( $hook_suffix )
			{
			
				if( $hook_suffix == 'post.php' || $hook_suffix == 'post-new.php' ) 
				{
					wp_enqueue_script('angular', JM_TC_JS_URL.'angular.min.js', false, null, false);
					wp_enqueue_script('jm-tc-metabox', JM_TC_JS_URL.'jm-tc-meta-box.js', array('angular'), null, false);
				}
			
			}
		*/
		
		/**
		* Admin page markup. Mostly handled by CMB
		* @since  0.1.0
		*/
		public function admin_page_display() {
			?>
			<div class="wrap">
			
			<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

			<?php cmb_metabox_form( $this->option_fields(), static::$key ); ?>
			<div class="doc-valid">
			<?php echo static::docu_links(0); ?>
			</div>
			
			<blockquote>
			<p class="bold"><?php _e('Get more <br />from 140 characters', 'jm-tc');?> </p>
			<p class="sub-bold"><?php _e('with Twitter Cards', 'jm-tc');?></p>
			<p class="card-desc"><?php _e('Twitter Cards help you richly represent your content within<br /> Tweets across the web and on mobile devices. This gives users <br />greater context and insight into the URLs shared on Twitter,<br /> which in turn allows Twitter to<br /> send more engaged traffic to your site or app.', 'jm-tc');?></p>
			</blockquote>
			
			<p class="plugin-desc"><?php _e('With this plugin you can get summary, summary large image, product, photo, gallery, app and player cards', 'jm-tc') ; ?></p>

			</div>
			<?php
		}
		
		/**
		* Defines the theme option metabox and field configuration
		* @since  0.1.0
		* @return array
		*/
		public static function option_fields() {
			
			// Only need to initiate the array once per page-load
			if ( ! empty( static::$plugin_options ) )
			return static::$plugin_options;
			
			static::$plugin_options = array(
			'id'         => 'jm_tc',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( static::$key, ), ),
			'show_names' => true,
			'fields'     => array(
			
			array(
			'name' 		=> __( 'Creator (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the creator of content?', 'jm-tc'),
			'id'   		=> 'twitterCardCreator',
			'type' 		=> 'text_medium',
			),

			array(
			'name' 		=> __( 'Site (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the Owner of the Website? (could be a trademark)',  'jm-tc'),
			'id'   		=> 'twitterCardSite',
			'type' 		=> 'text_medium',
			),				
			
			array(
			'name' 		=> __( 'Card Types', 'jm-tc' ),
			'desc' 		=> __( 'Choose what type of card you want to use', 'jm-tc'),
			'id'   		=> 'twitterCardType',
			'type' 		=> 'select',
			'options' 	=> array(
			'summary' 				=> __( 'Summary', 'jm-tc' ),
			'summary_large_image' 	=> __( 'Summary below Large Image', 'jm-tc' ),
			'photo' 				=> __( 'Photo', 'jm-tc' ),
			'app'					=> __( 'Application', 'jm-tc' ),
			)
			),

			)
			);
			
			
			return static::$plugin_options;
		}
		
		/**
		* Make public the protected $key variable.
		* @since  0.1.0
		* @return string  Option key
		*/
		public static function key() {
			return static::$key;
		}
		
	}
}