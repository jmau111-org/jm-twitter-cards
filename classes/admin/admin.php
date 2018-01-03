<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utilities;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Main {

	protected $settings_api;
	protected $subpages;

	/**
	 * Admin constructor.
	 */
	public function __construct() {

		$this->settings_api = new Settings();
		$this->subpages     = array(
			'jm_tc_import_export' => __( 'Import' ) . ' / ' . __( 'Export' ),
			'jm_tc_about'         => __( 'About' ),
		);

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts and styles
	 * @param $hook_suffix
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		/**
		 * Char count utility
		 **************************************************************************************************************/
		wp_register_script( 'count-chars', JM_TC_URL . 'js/charcount.js', array(
			'jquery',
		), JM_TC_VERSION, true );
		wp_localize_script(
			'count-chars',
			'_tcStrings',
			array(
				'message' => __( 'characters left', 'jm-tc' ),
			)
		);

		/**
		 * Main page
		 **************************************************************************************************************/
		if ( 'toplevel_page_jm_tc' === $hook_suffix ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'settings', JM_TC_URL . 'js/settings.js', array(
				'jquery',
			), JM_TC_VERSION, true );
			wp_enqueue_script( 'count-chars' );
		}

		/**
		 * Import Export page
		 **************************************************************************************************************/
		if ( 'jm-twitter-cards_page_jm_tc_import_export' === $hook_suffix ) {
			wp_enqueue_style( 'import-export', JM_TC_URL . 'css/import-export.css', array(), JM_TC_VERSION );
		}

		/**
		 * About page
		 **************************************************************************************************************/
		if ( 'jm-twitter-cards_page_jm_tc_about' === $hook_suffix ) {
			wp_enqueue_script( 'about', JM_TC_URL . 'js/about.js', array(
				'jquery',
			), JM_TC_VERSION, true );
			wp_localize_script(
				'about',
				'tcGitHub',
				array(
					'user'         => 'tweetpressfr',
					'repositories' => Utilities::get_github_repositories()
				)
			);
		}
	}

	public function admin_init() {
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		$this->settings_api->admin_init();
	}

	public function admin_menu() {
		add_menu_page( __( 'JM Twitter Cards', 'jm-tc' ), __( 'JM Twitter Cards', 'jm-tc' ), 'manage_options', 'jm_tc', array(
			$this,
			'plugin_page'
		), 'dashicons-twitter' );

		foreach ( $this->subpages as $page => $title ) {
			add_submenu_page( 'jm_tc', $title, $title, 'manage_options', $page, array(
				$this,
				'get_view'
			) );
		}
	}

	/**
	 * Register tabs
	 * @return array
	 */
	public function get_settings_sections() {
		$sections = array();
		require_once( JM_TC_DIR . 'views/settings-sections.php' );

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {

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
	 * @author Julien Maury
	 */
	public function get_view() {

		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array_keys( $this->subpages ), true ) ) {
			// don't allow anything to be loaded
			ob_start();
			$slug = str_replace( 'jm_tc_', '', sanitize_title_with_dashes( $_GET['page'] ) );
			$slug = str_replace( '_', '-', $slug );
			require( JM_TC_DIR . 'views/' . $slug . '.php' );
			ob_end_flush();
		}

	}

	/**
	 * Display options
	 */
	public function plugin_page() {
		echo '<div class="wrap tc">';
		echo '<h1>' . __( 'JM Twitter Cards', 'jm-tc' ) . '</h1>';
		$this->settings_api->show_forms();
		echo '</div>';
	}

}