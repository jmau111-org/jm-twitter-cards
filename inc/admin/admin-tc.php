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
		protected static $key = 'jm_tc_options';
		
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
			$this->title = __( 'JM Twitter Cards', 'jm-tc' );
		}
		
		/**
		* Initiate our hooks
		* @since 0.1.0
		*/
		public function hooks() {
			add_action( 'admin_init', array( $this, 'mninit' ) );
			add_action( 'admin_menu', array( $this, 'add_page' ) );
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
			'#analytics'

			);
			$docu  = '<a class="button button-secondary docu" target="_blank" href="' . esc_url(admin_url().'admin.php?page=jm_tc_doc') . $anchor[$n] . '">' . __('Documentation', 'jm-tc') . '</a>';
			$docu .= '&nbsp;<a class="button button-secondary docu" target="_blank" href="' . esc_url('https://dev.twitter.com/docs/cards/validation/validator') . '">' . __('Validator', 'jm-tc') . '</a>';
			
			return $docu;
		}
		
		
		
		
		/**
		* Add menu options page
		* @since 0.1.0
		*/
		public function add_page() {
			$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', static::$key, array( $this, 'admin_page_display' ) );
			$this->options_subpage_doc = add_submenu_page( 'jm_tc_options', __( 'Options', 'jm-tc' ), __( 'Options', 'jm-tc' ) , 'manage_options', static::$key, array( $this, 'admin_page_display' ) );
			$this->options_subpage_doc = add_submenu_page( 'jm_tc_options', __( 'Documentation', 'jm-tc' ), __( 'Documentation', 'jm-tc' ) , 'manage_options', 'jm_tc_doc', 'jm_tc_subpages' );
			$this->options_subpage_about = add_submenu_page( 'jm_tc_options', __( 'About' ), __( 'About') , 'manage_options', 'jm_tc_about', 'jm_tc_subpages' );
			
			add_action( 'load-' . $this->options_page, array( $this, 'load_admin_scripts' ) );
			add_action( 'load-' . $this->options_subpage_doc, array( $this, 'load_admin_doc_scripts' ) );
		}
		
		
		// I prefer this way even if it's not so good^^
		public function load_admin_scripts()
		{
			wp_enqueue_script('jm-tc-admin-script', JM_TC_JS_URL.'jm-tc-admin.js'
			, array('jquery') 
			, '1.0'
			, true
			);
		}
		
		
		public function load_admin_doc_scripts()
		{
			wp_enqueue_style('jm-tc-doc-style', JM_TC_CSS_URL.'jm-tc-documentation.css');
			load_plugin_textdomain('jm-tc-doc', false, JM_TC_LANG_DIR);
		}

		
		/**
		* Admin page markup. Mostly handled by CMB
		* @since  0.1.0
		*/
		public function admin_page_display() {
			?>
			<div class="wrap" style="max-width:1024px;">
			<h2><i class="dashicons dashicons-twitter" style="font-size:2em; margin-right:1em; color:#292F33;"></i> <?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb_metabox_form( $this->option_fields(), static::$key ); ?>
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
			'id'         => 'jm_tc_options',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( static::$key, ), ),
			'show_names' => true,
			'fields'     => array(
			
			array(
			'type' => 'title',
			'name' => __( 'General', 'jm-tc' ),
			'id'   => 'general_title', // Not used but needed for plugin
			'desc' => static::docu_links(0),
			),
			
			
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
			'name' 		=> __( 'Excerpt by default', 'jm-tc' ),
			'desc' 		=> __('By default the plugin grabs the first words of posts to create the mandatory meta description for twitter cards.', 'jm-tc'),
			'id'   		=> 'twitterCardExcerptLength',
			'type' 		=> 'text_number',
			'min'		=> 15,
			),					
			
			array(
			'name' 		=> __( 'Card Types', 'jm-tc' ),
			'desc' 		=> __( 'Choose what type of card you want to use', 'jm-tc'),
			'id'   		=> 'twitterCardType',
			'type' 		=> 'select',
			'options' 	=> array(
			'summary' 				=> __( 'Summary', 'jm-tc' ),
			'summary_large_image' 	=> __( 'Summary Large Card', 'jm-tc' ),
			'photo' 				=> __( 'Photo', 'jm-tc' ),
			'app'					=> __( 'App', 'jm-tc' ),
			)
			),
			
			array(
			'type' => 'title',
			'name' => __( 'Robots.txt', 'jm-tc' ),
			'id'   => 'robots_txt_title', // Not used but needed for plugin
			'desc' => __('Important !', 'jm-tc'),
			),

			array(
			'name' 		=> __( 'Twitter\'s bot', 'jm-tc' ),
			'desc' 		=> __('Add required rules in robots.txt', 'jm-tc'),
			'id'   		=> 'twitterCardRobotsTxt',
			'type' 		=> 'select',
			'options'	 => array(
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			'no' 			=> __( 'No', 'jm-tc' ),
			)
			),
			
			
			array(
			'type' => 'title',
			'name' => __( 'Multi-author', 'jm-tc' ),
			'id'   => 'multi_author_title', // Not used but needed for plugin
			),	
			
			array(
			'name' 		=> __( 'Meta key Twitter', 'jm-tc' ),
			'desc' 		=> __('Modify user meta key associated with Twitter Account in profiles :', 'jm-tc'),
			'id'   		=> 'twitterCardUsernameKey', 
			'type' 		=> 'text_medium',
			),
			
			array(
			'type' => 'title',
			'name' => __( 'SEO', 'jm-tc' ),
			'id'   => 'meta_box_title', // Not used but needed for plugin
			'desc' => static::docu_links(3),
			),				
			
			
			array(
			'name' 		=> __( 'Meta title', 'jm-tc' ),
			'desc' 		=> __('Use SEO by Yoast or All in ONE SEO meta title for your cards (<strong>default is yes</strong>)', 'jm-tc'),
			'id'   		=> 'twitterCardSEOTitle',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),
			
			array(
			'name' 		=> __( 'Meta Desc', 'jm-tc' ),
			'desc' 		=> __('Use SEO by Yoast or All in ONE SEO meta description for your cards (<strong>default is yes</strong>)', 'jm-tc'),
			'id'   		=>  'twitterCardSEODesc',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),
			//'
			
			array(
			'name' 		=> __( 'Custom field title', 'jm-tc' ),
			'desc' 		=> __('If you prefer to use your own fields', 'jm-tc'),
			'id'   		=> 'twitterCardTitle',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Custom field desc', 'jm-tc' ),
			'desc' 		=> __('If you prefer to use your own fields', 'jm-tc'),
			'id'   		=> 'twitterCardDesc',
			'type' 		=> 'text_medium',
			),
			
			array(
			'type' => 'title',
			'name' => __( 'Home', 'jm-tc' ),
			'id'   => 'home_section_title', // Not used but needed for plugin
			'desc' => static::docu_links(2),
			),	
			
			array(
			'name' 		=> __( 'Home meta desc', 'jm-tc' ),
			'desc' 		=> __('Enter desc for Posts Page (max: 200 words)', 'jm-tc'),
			'id'   		=> 'twitterCardPostPageDesc',
			'type' 		=> 'textarea_small',
			),

			array(
			'type' => 'title',
			'name' => __( 'Images', 'jm-tc' ),
			'id'   => 'images_title', // Not used but needed for plugin
			'desc' => static::docu_links(4),
			),	
			
			
			array(
			'name' => __( 'Images', 'jm-tc' ),
			'id'   => 'twitterCardImage', // Not used but needed for plugin
			'desc' => __('Enter URL for fallback image (image by default)', 'jm-tc'),
			'type' => 'file',
			),	
			
			
			array(
			'name'     => __( 'Define specific size for twitter:image display', 'jm-tc'),
			'id'       => 'twitterCardImageSize',
			'type'     => 'select',

			'options'  => array(
			'jmtc-max-mobile-non-retina-thumb' => __('Max mobile non retina (width: 280px - height: 375px)', 'jm-tc'),
			'jmtc-max-mobile-retina-thumb' => __('Max mobile retina (width: 560px - height: 750px)', 'jm-tc'),
			'jmtc-max-web-thumb' => __('Max web size(width: 435px - height: 375px)', 'jm-tc'),
			'jmtc-small-thumb' => __('Small (width: 280px - height: 150px)', 'jm-tc'),
			),

			),
			
			array(
			'name' 		=> __( 'Crop', 'jm-tc' ),
			'desc' 		=> __('Do you want to force crop on card Image?', 'jm-tc'),
			'id'   		=> 'twitterCardCrop',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),

			array(
			'name' 		=> __('Image width', 'jm-tc'),
			'desc' 		=> __('px', 'jm-tc'),
			'id'   		=> 'twitterCardImageWidth',
			'type' 		=> 'text_number',
			'min'		=> 280,
			),	

			array(
			'name' 		=> __('Image width', 'jm-tc'),
			'desc' 		=> __('px', 'jm-tc'),
			'id'   		=> 'twitterCardImageHeight',
			'type' 		=> 'text_number',
			'min'		=> 150,
			),	
			
			array(
			'type' => 'title',
			'name' => __( 'Categories and custom taxonomies', 'jm-tc' ),
			'id'   => 'taxonomies_title', // Not used but needed for plugin
			'desc' => __('For all the following fields, if you do not want to use leave it blank','jm-tc'),
			),	
			
			/*
			array(
				'name' 		=> 'Taxonomies',
				'desc' 		=> 'Just select taxonomie that should have markup for Twitter Cards',
				'id' 		=> 'twitterCardTaxonomies',
				'taxonomy' 	=> get_taxonomies(),
				'type' 		=> 'taxonomy_multicheck',    
			),*/
						
			array(
			'type' => 'title',
			'name' => __( 'Deep Linking', 'jm-tc' ),
			'id'   => 'deep_linking_title', // Not used but needed for plugin
			'desc' => static::docu_links(5)."\n".__('For all the following fields, if you do not want to use leave it blank but be careful with the required markup for your app. Read the documentation please.','jm-tc'),
			),	
			
			array(
			'name' 		=> __( 'Deep linking? ', 'jm-tc'),
			'desc' 		=> __('Reserved for advanced users', 'jm-tc'),
			'id'   		=> 'twitterCardDeepLinking',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),

			array(
			'name' 		=> __( 'iPhone Name', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone Name ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( ' iPhone URL', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone URL ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPhone ID', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone ID ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneId',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad Name', 'jm-tc' ),
			'desc' 		=> __('Enter iPad Name ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad URL', 'jm-tc' ),
			'desc' 		=> __('Enter iPad URL ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad ID', 'jm-tc' ),
			'desc' 		=> __('Enter iPad ID ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadId',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play Name', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play Name ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play URL', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play URL ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play ID', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play ID ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayId',
			'type' 		=> 'text_medium',
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