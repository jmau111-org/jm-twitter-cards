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

		$this->settings_api = new Settings();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}


	/**
	 * Add some js
	 *
	 * @param $hook_suffix
	 * for fox desc home - charcount
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		wp_register_script( 'jm-tc-charcount', JM_TC_URL . 'js/charcount.js', array( 'jquery' ), JM_TC_VERSION, true );

		if ( 'toplevel_page_jm_tc' === $hook_suffix ) {
			wp_enqueue_script( 'jm-tc-charcount' );
			wp_localize_script(
				'jm-tc-charcount',
				'tcStrings',
				array(
					'charcount_message' => __( 'characters left', 'jm-tc' ),
				)
			);
		}
	}


	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	function admin_menu() {
		add_menu_page( __( 'JM Twitter Cards', 'jm-tc' ), __( 'JM Twitter Cards', 'jm-tc' ), 'manage_options', 'jm_tc', array(
			$this,
			'plugin_page'
		), 'dashicons-twitter' );
		add_submenu_page( 'jm_tc', __( 'Import' ) . ' / ' . __( 'Export' ), __( 'Import' ) . ' / ' . __( 'Export' ), 'manage_options', 'jm_tc_import_export', array(
			$this,
			'get_view_exp'
		) );
		add_submenu_page( 'jm_tc', __( 'About' ), __( 'About' ), 'manage_options', 'jm_tc_about', array(
			$this,
			'get_view_about'
		) );
	}

	/**
	 * Register tabs
	 * @return array
	 */
	function get_settings_sections() {
		$sections = array();
		require_once( JM_TC_DIR . 'views/settings-sections.php' );

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {

		$settings_fields = array();
		require_once( JM_TC_DIR . 'views/settings.php' );

		return $settings_fields;
	}

	/**
	 * Simply get post types
	 *
	 * @param array $args
	 *
	 * @author Julien Maury
	 * @return array
	 */
	public function get_post_types( $args = array() ) {

		$defaults = array( 'public' => true, );
		$pt_args  = apply_filters( 'jm_tc_cpt_args', wp_parse_args( $args, $defaults ) );

		if ( ! is_array( $pt_args ) ) {
			$pt_args = array();
		}

		return get_post_types( $pt_args );
	}

	/**
	 * Get our view
	 */
	public function get_view_exp() {
		ob_start();
		require( JM_TC_DIR . 'views/settings-import-export.php' );
		ob_end_flush();

		return true;
	}

	/**
	 * Get our view
	 */
	public function get_view_about() {
		ob_start();
		require( JM_TC_DIR . 'views/about.php' );
		ob_end_flush();

		return true;
	}

	/**
	 * Display options
	 */
	function plugin_page() {
		echo '<div class="wrap">';
		echo '<h1>' . __( 'JM Twitter Cards', 'jm-tc' ) . '</h1>';
		$this->settings_api->show_forms();
		echo '</div>';
	}

}