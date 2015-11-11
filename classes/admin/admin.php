<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Main {

	private $settings_api;

	/**
	 * Admin constructor.
	 */
	public function __construct() {

		$this->settings_api = new \WeDevs_Settings_API;

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}


	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	function admin_menu() {
		add_menu_page( __( 'JM Twitter Cards', 'jm-tc' ), __( 'JM Twitter Cards', 'jm-tc' ), 'manage_options', 'jm_tc', array( $this, 'plugin_page' ), 'dashicons-twitter' );
		add_submenu_page( 'jm_tc', __( 'Import' ) . ' / ' . __( 'Export' ), __( 'Import' ) . ' / ' . __( 'Export' ), 'manage_options', 'jm_tc_import_export', array( $this, 'get_view' ) );
	}

	/**
	 * Register tabs
	 * @return array
	 */
	function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'jm_tc',
				'title' => __( 'Options', 'jm-tc' )
			),
			array(
				'id'    => 'jm_tc_cpt',
				'title' => __( 'Post types' )
			),
		);

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$settings_fields = array(
			'jm_tc' => array(
				array(
					'name'  => 'twitterCreator',
					'label' => __( 'Creator (twitter username)', 'jm-tc' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'twitterSite',
					'label' => __( 'Site (twitter username)', 'jm-tc' ),
					'type'  => 'text',
				),
				array(
					'name'    => 'twitterCardType',
					'label'   => __( 'Card type', 'jm-tc' ),
					'type'    => 'select',
					'default' => 'summary',
					'options' => array(
						'summary'             => __( 'Summary', 'jm-tc' ),
						'summary_large_image' => __( 'Summary below Large Image', 'jm-tc' ),
						'app'                 => __( 'Application', 'jm-tc' ),
					)
				),
				array(
					'name'    => 'twitterCardExcerpt',
					'label'   => __( 'Excerpt' ),
					'desc'    => __( 'Excerpt as meta desc?', 'jm-tc' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'yes', 'jm-tc' ),
						'no'  => __( 'no', 'jm-tc' ),
					)
				),
				array(
					'name'    => 'twitterCardOg',
					'label'   => __( 'Open Graph', 'jm-tc' ),
					'desc'    => __( 'Open Graph/SEO', 'jm-tc' ),
					'type'    => 'radio',
					'default' => 'no',
					'options' => array(
						'no'  => __( 'no', 'jm-tc' ),
						'yes' => __( 'yes', 'jm-tc' ),
					)
				),
				array(
					'name'    => 'twitterImage',
					'label'   => __( 'Image Fallback', 'jm-tc' ),
					'type'    => 'file',
					'default' => ''
				),
				array(
					'name'    => 'twitterCardImgSize',
					'label'   => __( 'Define specific size for twitter:image display', 'jm-tc' ),
					'type'    => 'select',
					'default' => 'small',
					'options' => array(
						'small'             => __( '280 x 375 px', 'jm-tc' ),
						'web'               => __( '560 x 750 px', 'jm-tc' ),
						'mobile-non-retina' => __( '435 x 375 px', 'jm-tc' ),
						'mobile-retina'     => __( '280 x 150 px', 'jm-tc' ),
					)
				),
				array(
					'label' => __( 'Home meta desc', 'jm-tc' ),
					'desc'  => __( 'Enter desc for Posts Page (max: 200 characters)', 'jm-tc' ),
					'name'  => 'twitterPostPageDesc',
					'type'  => 'textarea',
				),
				array(
					'label' => __( 'iPhone Name', 'jm-tc' ),
					'desc'  => __( 'Enter iPhone Name ', 'jm-tc' ),
					'name'  => 'twitteriPhoneName',
					'type'  => 'text',
				),
				array(
					'label' => __( ' iPhone URL', 'jm-tc' ),
					'desc'  => __( 'Enter iPhone URL ', 'jm-tc' ),
					'name'  => 'twitteriPhoneUrl',
					'type'  => 'text',
				),
				array(
					'label' => __( 'iPhone ID', 'jm-tc' ),
					'desc'  => __( 'Enter iPhone ID ', 'jm-tc' ),
					'name'  => 'twitteriPhoneId',
					'type'  => 'text',
				),
				array(
					'label' => __( 'iPad Name', 'jm-tc' ),
					'desc'  => __( 'Enter iPad Name ', 'jm-tc' ),
					'name'  => 'twitteriPadName',
					'type'  => 'text',
				),
				array(
					'label' => __( 'iPad URL', 'jm-tc' ),
					'desc'  => __( 'Enter iPad URL ', 'jm-tc' ),
					'name'  => 'twitteriPadUrl',
					'type'  => 'text',
				),
				array(
					'label' => __( 'iPad ID', 'jm-tc' ),
					'desc'  => __( 'Enter iPad ID ', 'jm-tc' ),
					'name'  => 'twitteriPadId',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Google Play Name', 'jm-tc' ),
					'desc'  => __( 'Enter Google Play Name ', 'jm-tc' ),
					'name'  => 'twitterGooglePlayName',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Google Play URL', 'jm-tc' ),
					'desc'  => __( 'Enter Google Play URL ', 'jm-tc' ),
					'name'  => 'twitterGooglePlayUrl',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Google Play ID', 'jm-tc' ),
					'desc'  => __( 'Enter Google Play ID ', 'jm-tc' ),
					'name'  => 'twitterGooglePlayId',
					'type'  => 'text',
				),
				array(
					'label' => __( 'App Country code', 'jm-tc' ),
					'desc'  => __( 'Enter 2 letter App Country code in case your app is not available in the US app store', 'jm-tc' ),
					'name'  => 'twitterAppCountry',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Custom field title', 'jm-tc' ),
					'desc'  => __( 'If you prefer to use your own field for twitter meta title instead of SEO plugin. Leave it blank if you want to use SEO plugin or default title.', 'jm-tc' ),
					'name'  => 'twitterCardTitle',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Custom field desc', 'jm-tc' ),
					'desc'  => __( 'If you prefer to use your own field for twitter meta description instead of SEO plugin. Leave it blank if you want to use SEO plugin or default desc.', 'jm-tc' ),
					'name'  => 'twitterCardDesc',
					'type'  => 'text',
				),
			),
			'jm_tc_cpt' => array(
				array(
					'name'    => 'twitterCardPt',
					'label'   => __( 'Select' ),
					'desc'    => __( 'Default' ) . ': ' . __( 'All' ),
					'type'    => 'multicheck',
					'options' => $this->get_post_types()
				),
			),
		);

		return $settings_fields;
	}

	/**
	 * Simply get post types
	 *
	 * @param array $args
	 * @author Julien Maury
	 * @return array
	 */
	public function get_post_types( $args = array() ) {

		$defaults = array(
			'public' => true,
		);
		$pt_args = apply_filters( 'jm_tc_cpt_args', wp_parse_args ( $args, $defaults ) );

		if ( ! is_array( $pt_args ) ) {
			$pt_args = array();
		}

		return get_post_types( $pt_args );
	}

	/**
	 * Get our view
	 */
	public function get_view(){
		ob_start();
		require( JM_TC_DIR . 'views/settings.php' );
		ob_end_flush();
		return true;
	}

	/**
	 * Display options
	 */
	function plugin_page() {
		echo '<div class="wrap">';
		echo '<h1>' . __( 'JM Twitter Cards', 'jm-tc' ) . '</h1>';
		$this->settings_api->show_navigation();
		$this->settings_api->show_forms();
		echo '</div>';
	}

}