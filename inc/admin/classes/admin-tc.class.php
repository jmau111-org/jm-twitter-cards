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
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_scripts' ) );
			add_filter( 'cmb_frontend_form_format', array( $this, 'save_button' ), 10, 3 );
			add_action( 'cmb_save_options-page_fields', array( $this, 'is_it_saved') );
		}
		
		/**
		*
		* Alter the submit button with the sumbit_button() function
		* This is more appropriate and it will display a notice when settings are saved
		*/
		public function save_button( $object_id, $meta_box, $form ){
		
			$form = '<form class="cmb-form" method="post" id="%s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%s">%s<input type="submit" name="submit-cmb" value="%s" class="button button-primary"></form>';
			
			return $form;
		}
		
		/**
		* Register our setting to WP
		* @since  0.1.0
		*/
		public function mninit() {
			register_setting( self::$key, self::$key );
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
		* Call admin pages
		*
		* */
		public function subpages()
		{
			if ( isset( $_GET['page'] ) ) 
			{
				switch ( $_GET['page'] ) 
				{
					case 'jm_tc_cf':
						require( JM_TC_ADMIN_PAGES_DIR .'custom_fields.php' );
						break;
					case 'jm_tc_import_export':
						require( JM_TC_ADMIN_PAGES_DIR .'import-export.php' );
						break;

					case 'jm_tc_images':
						require( JM_TC_ADMIN_PAGES_DIR .'images.php' );	
						break;
						
					case 'jm_tc_meta_box':
						require( JM_TC_ADMIN_PAGES_DIR .'meta_box.php' );	
						break;

					case 'jm_tc_multi_author':
						require( JM_TC_ADMIN_PAGES_DIR .'multi_author.php' );
						break;

					case 'jm_tc_home':
						require( JM_TC_ADMIN_PAGES_DIR .'home.php' );	
						break;

					case 'jm_tc_robots':
						require( JM_TC_ADMIN_PAGES_DIR .'robots.php' );
						break;

					case 'jm_tc_deep_linking':
						require( JM_TC_ADMIN_PAGES_DIR .'deep_linking.php' );
						break;

					case 'jm_tc_analytics':
						require( JM_TC_ADMIN_PAGES_DIR .'analytics.php' );
						break;

					case 'jm_tc_doc':
						require( JM_TC_ADMIN_PAGES_DIR .'documentation.php' );
						break;

					case 'jm_tc_about':
						require( JM_TC_ADMIN_PAGES_DIR .'about.php' );
						break;
						
					case 'jm_tc_tutorial':
						require( JM_TC_ADMIN_PAGES_DIR .'tutorial.php' );
						break;

				}
			}
		}

		
		/**
		* Add menu options page
		* @since 0.1.0
		*/
		public function add_page() {
		
			$this->options_page 					= add_menu_page( $this->title, $this->title, 'manage_options', self::$key, array( $this, 'admin_page_display' ), 'dashicons-twitter');
			$this->options_page_options 			= add_submenu_page( 'jm_tc', __('General'), __('General'), 'manage_options', self::$key, array( $this, 'admin_page_display' ) );
			$this->options_page_import_export		= add_submenu_page( 'jm_tc', __('Import').' / '.__('Export'), __('Import').' / '.__('Export'), 'manage_options', 'jm_tc_import_export', array( $this, 'subpages' ));			
			$this->options_subpage_tutorial 		= add_submenu_page( 'jm_tc', __( 'Tutorial' ), __( 'Tutorial' ) , 'manage_options', 'jm_tc_tutorial', array( $this, 'subpages' ));
			
			$this->options_subpage_images 			= add_submenu_page( 'jm_tc', __( 'Images', 'jm-tc' ), __( 'Images', 'jm-tc' ) , 'manage_options', 'jm_tc_images', array( $this, 'subpages' ));
			$this->options_subpage_cf				= add_submenu_page( 'jm_tc', __( 'Custom fields', 'jm-tc' ), __( 'Custom fields', 'jm-tc' ) , 'manage_options', 'jm_tc_cf', array( $this, 'subpages' ));
			$this->options_subpage_robots 			= add_submenu_page( 'jm_tc', __( 'robots.txt', 'jm-tc' ), __( 'robots.txt', 'jm-tc' ) , 'manage_options', 'jm_tc_robots', array( $this, 'subpages' ));
			$this->options_subpage_home 			= add_submenu_page( 'jm_tc', __( 'Home settings', 'jm-tc' ), __( 'Home settings', 'jm-tc' ) , 'manage_options', 'jm_tc_home', array( $this, 'subpages' ));
			
			$this->options_subpage_metabox			= add_submenu_page( 'jm_tc', __( 'Meta Box', 'jm-tc' ),  __( 'Meta Box', 'jm-tc' ), 'manage_options', 'jm_tc_meta_box', array( $this, 'subpages' ));
			
			//there is no point displaying this option page is the website is not multi_author !
			if ( is_multi_author() ) 
				$this->options_subpage_multi_author = add_submenu_page( 'jm_tc', __( 'Multi Author', 'jm-tc' ), __( 'Multi Author', 'jm-tc') , 'manage_options', 'jm_tc_multi_author', array( $this, 'subpages' ));
			
			$this->options_subpage_deep_linking 	= add_submenu_page( 'jm_tc', __( 'Deep Linking', 'jm-tc' ), __( 'Deep Linking', 'jm-tc' ) , 'manage_options', 'jm_tc_deep_linking', array( $this, 'subpages' ));
			$this->options_subpage_doc 				= add_submenu_page( 'jm_tc', __( 'Documentation', 'jm-tc' ),  __( 'Documentation', 'jm-tc' ) , 'manage_options', 'jm_tc_doc', array( $this, 'subpages' ));
			$this->options_subpage_analytics 		= add_submenu_page( 'jm_tc', __( 'Analytics', 'jm-tc' ), __( 'Analytics', 'jm-tc' ) , 'manage_options', 'jm_tc_analytics', array( $this, 'subpages' ));		
			$this->options_subpage_about 			= add_submenu_page( 'jm_tc', __( 'About' ), __( 'About' ) , 'manage_options', 'jm_tc_about', array( $this, 'subpages' ));
			
		}
		

		// Better to use $hook_suffix here for scripts
		public function admin_scripts( $hook_suffix )
		{

			//var_dump( $hook_suffix );

			switch ( $hook_suffix ) 
			{

				case 'toplevel_page_jm_tc':
				case 'jm-twitter-cards_page_jm_tc_import_export':
				case 'jm-twitter-cards_page_jm_tc_tutorial':
				case 'jm-twitter-cards_page_jm_tc_doc':
				case 'jm-twitter-cards_page_jm_tc_meta_box':
				case 'jm-twitter-cards_page_jm_tc_about':
				case 'jm-twitter-cards_page_jm_tc_cf':
				case 'jm-twitter-cards_page_jm_tc_images':
				case 'jm-twitter-cards_page_jm_tc_robots':
				case 'jm-twitter-cards_page_jm_tc_multi_author':
				case 'jm-twitter-cards_page_jm_tc_deep_linking':
				case 'jm-twitter-cards_page_jm_tc_analytics':

					wp_enqueue_style( 'jm-tc-admin-style', JM_TC_CSS_URL.'jm-tc-admin.css' );

				break;

				case 'post.php':
				case 'post-new.php':
					
					wp_enqueue_style( 'jm-tc-metabox', JM_TC_CSS_URL.'jm-tc-meta-box.css' );
					wp_enqueue_script( 'jm-tc-metabox', JM_TC_JS_URL.'jm-tc-meta-box.js', array('jquery'), null, false );

				break;

				case 'jm-twitter-cards_page_jm_tc_home':
					
					wp_enqueue_style( 'jm-tc-admin-style', JM_TC_CSS_URL.'jm-tc-admin.css' );
					wp_enqueue_script( 'jm-tc-admin-script', JM_TC_JS_URL.'jm-tc-admin-home.js'
						, array('jquery') 
						, '1.0'
						, true
					);

				break;

			}
			
		}
		
		
		// Add a confirmation message 
		public function is_it_saved() 
		{
			?>
			<div id="message" class="updated">
			<p><strong><?php _e('Settings saved.');?></strong></p>
			</div>
			<?php
		}
		
		
		public function load_admin_doc_scripts()
		{
			
			load_plugin_textdomain('jm-tc-doc', false, JM_TC_LANG_DIR);
		}
		
		
		/**
		* Admin page markup. Mostly handled by CMB
		* @since  0.1.0
		*/
		public function admin_page_display() {
			?>
			<div class="wrap">
			
			<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>
			
			<?php echo JM_TC_Tabs::admin_tabs(); ?>

			<?php cmb_metabox_form( $this->option_fields(), self::$key ); ?>
			<div class="doc-valid">
			<?php echo self::docu_links(0); ?>
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
			if ( ! empty( self::$plugin_options ) )
			return self::$plugin_options;
			
			self::$plugin_options = array(
			'id'         => self::$key,
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'show_names' => true,
			'fields'     => array(
			
			array(
			'name' 		=> __( 'Creator (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the creator of content?', 'jm-tc'),
			'id'   		=> 'twitterCreator',
			'type' 		=> 'text_medium',
			),

			array(
			'name' 		=> __( 'Site (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the Owner of the Website? (could be a trademark)',  'jm-tc'),
			'id'   		=> 'twitterSite',
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
			
			array(
			'name' 		=> __( 'Open Graph', 'jm-tc' ),
			'desc' 		=> __( 'Open Graph/SEO', 'jm-tc'),
			'id'   		=> 'twitterCardOg',
			'type' 		=> 'select',
			'options' 	=> array(
			'no' 		=> __( 'no', 'jm-tc' ),
			'yes' 		=> __( 'yes', 'jm-tc' ),
			)
			),

			array(
			'name' 		=> __( 'Excerpt'),
			'desc' 		=> __( 'Excerpt as meta desc?', 'jm-tc'),
			'id'   		=> 'twitterCardExcerpt',
			'type' 		=> 'select',
			'options' 	=> array(
			'no' 		=> __( 'no', 'jm-tc' ),
			'yes' 		=> __( 'yes', 'jm-tc' ),
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
		public static function key() {
			return self::$key;
		}
		
	}
}