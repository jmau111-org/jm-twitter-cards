<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists( 'JM_TC_Admin' ) ) {

	class JM_TC_Network extends JM_TC_Admin{
		
		/**
		* Option key, and option page slug
		* @var string
		*/
		protected static $key = 'jm_tc_network';
		
		/**
		* Constructor
		* @since 0.1.0
		*/
		public function __construct() {
		
			// Set our title
			$this->title = __( 'JM Twitter Cards', 'jm-tc');
			add_action( 'network_admin_menu', array( $this, 'network_page') );
			add_filter( 'cmb_override_option_get_jm_tc_network', array( $this, 'cmb_override_option_get_'), 10, 2 );
			add_filter( 'cmb_override_option_save_jm_tc_network', array( $this, 'cmb_override_option_save_'), 10, 2 );
			
		}
		
		/**
		* Override getting and setting props to jtsternberg
		*/
		// Override CMB's getter

		function cmb_override_option_get_( $empty, $default ) {
			return get_site_option( 'jm_tc_network', $default );
		}

		// Override CMB's setter
		function cmb_override_option_save_( $to_override, $value ) {
			return update_site_option( 'jm_tc_network', $value );
		}
				
		/**
		* Network admin.
		*/
		function network_page() {
			
			$this->options_page = add_menu_page( $this->title, $this->title, 'manage_network_options', self::$key, array( $this, 'network_admin_page_display'), JM_TC_URL.'img/bird_blue_16.png');

		}

		
		/**
		* Admin page markup. Mostly handled by CMB
		* @since  0.1.0
		*/
		public function network_admin_page_display() {
			?>
			<div class="wrap">
			
			<h2>JM Twitter Cards : <?php _e( 'Network', 'jm-tc' ); ?></h2>

			<?php cmb_metabox_form( $this->option_fields(), self::$key ); ?>
			<?php 	//$debug = new JM_TC_Options;
					//$debug->showVisible('JM_TC_Options'); 
			?>
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
			'name' 		=> __( 'Site (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the Owner of the Website? (could be a trademark)',  'jm-tc'),
			'id'   		=> 'twitterNetworkSite',
			'type' 		=> 'text_medium',
			),				

			
			array(
			'name' 		=> __( 'Open Graph', 'jm-tc' ),
			'desc' 		=> __( 'Open Graph/SEO', 'jm-tc'),
			'id'   		=> 'twitterNetworkCardOg',
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
		
		
	}
}