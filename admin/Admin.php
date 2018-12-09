<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utils as Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of this plugin.
	 */
	protected $version;

	protected $options;

	protected $settings_api;
	protected $sub_pages;

	protected $video_files;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Settings();

		$this->sub_pages = [
			'jm_tc_import_export' => __( 'Import', 'jm-tc' ) . ' / ' . __( 'Export', 'jm-tc' ),
			'jm_tc_about'         => __( 'About', 'jm-tc' ),
			'jm_tc_tutorials'     => __( 'Tutorials', 'jm-tc' ),
		];

		$this->video_files = [
			'302609444' => __( 'Setup for the first time', 'jm-tc' ),
			'302609402' => __( 'Setup metabox with custom post types', 'jm-tc' ),
			'302609437' => __( 'Dealing with images', 'jm-tc' ),
			'302609425' => __( 'Set first image found in post content as twitter image', 'jm-tc' ),
			'302609429' => __( 'Upgrading to Gutenberg', 'jm-tc' ),
			'305338709' => __( 'How to recover twitter cards sidebar after unpin', 'jm-tc' ),
		];
	}

	/**
	 * Re-add Settings link to admin page
	 * some users needed it and it does not evil ^^
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_action_link( $links ) {
		$links['settings'] = '<a href="' . add_query_arg( [ 'page' => JM_TC_SLUG_MAIN_OPTION ], admin_url( 'admin.php' ) ) . '">' . __( 'Settings', 'jm-tc' ) . '</a>';

		return $links;
	}


	/**
	 * Enqueue scripts and styles
	 *
	 * @param $hook_suffix
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		/**
		 * Char count utility
		 **************************************************************************************************************/
		wp_register_script( 'count-chars', JM_TC_URL . 'js/charcount' . Utilities::suffix_for_dev_env() . '.js', [
			'jquery',
		], JM_TC_VERSION, true );
		wp_localize_script(
			'count-chars',
			'_tcStrings',
			[
				'message' => __( 'characters left', 'jm-tc' ),
			]
		);

		/**
		 * Main page
		 **************************************************************************************************************/
		if ( 'toplevel_page_jm_tc' === $hook_suffix ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();
			wp_enqueue_style( 'settings', JM_TC_URL . 'css/settings' . Utilities::suffix_for_dev_env() . '.css' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'settings', JM_TC_URL . 'js/settings' . Utilities::suffix_for_dev_env() . '.js', [
				'jquery',
			], JM_TC_VERSION, true );
			wp_enqueue_script( 'count-chars' );
		}

		/**
		 * About page
		 **************************************************************************************************************/
		if ( 'jm-twitter-cards_page_jm_tc_about' === $hook_suffix ) {
			wp_enqueue_script( 'about', JM_TC_URL . 'js/about' . Utilities::suffix_for_dev_env() . '.js', [
				'jquery',
			], JM_TC_VERSION, true );
			wp_localize_script(
				'about',
				'tcGitHub',
				[
					'user'         => 'jmau111',
					'repositories' => Utilities::get_github_repositories(),
				]
			);
		}
	}

	public function admin_init() {

		load_plugin_textdomain( 'jm-tc', false, JM_TC_LANG_DIR );

		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		$this->settings_api->admin_init();
	}

	/**
	 * Register tabs
	 * @return array
	 */
	public function get_settings_sections() {
		$sections = [];
		require_once JM_TC_DIR . 'admin/views/settings-sections.php';

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {

		$settings_fields = [];
		$opts            = \jm_tc_get_options();

		require_once JM_TC_DIR . 'admin/views/settings.php';

		return $settings_fields;
	}

	public function admin_menu() {
		add_menu_page( __( 'JM Twitter Cards', 'jm-tc' ), __( 'JM Twitter Cards', 'jm-tc' ), 'manage_options', 'jm_tc', [
			$this,
			'plugin_page',
		], 'dashicons-twitter' );

		foreach ( $this->sub_pages as $page => $title ) {
			add_submenu_page( 'jm_tc', $title, $title, 'manage_options', $page, [
				$this,
				'get_view',
			] );
		}
	}

	/**
	 * Simply get post types
	 *
	 * @param array $args
	 *
	 * @author Julien Maury
	 * @return array
	 */
	public function get_post_types( $args = [] ) {

		$defaults = [ 'public' => true, ];
		$pt_args  = apply_filters( 'jm_tc_cpt_args', wp_parse_args( $args, $defaults ) );

		if ( ! is_array( $pt_args ) ) {
			$pt_args = [];
		}

		return get_post_types( $pt_args );
	}

	/**
	 * Get our view
	 * @author Julien Maury
	 */
	public function get_view() {

		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array_keys( $this->sub_pages ), true ) ) {
			// don't allow anything to be loaded
			ob_start();
			$slug = str_replace( 'jm_tc_', '', sanitize_title_with_dashes( $_GET['page'] ) );
			$slug = str_replace( '_', '-', $slug );
			require( JM_TC_DIR . 'admin/views/' . $slug . '.php' );
			ob_end_flush();
		}

	}

	/**
	 * Display options
	 */
	public function plugin_page() {
		echo '<div class="wrap tc">';
		echo '<h1>' . __( 'JM Twitter Cards', 'jm-tc' ) . '</h1>';

		if ( Utilities::gutenberg_exists() ) {
			echo Utilities::brand_new( __( '10.0.0 : There is now support for <a href="https://wordpress.org/gutenberg/">Gutenberg</a>, please use this custom sidebar. Just click on the Twitter Icon on your edit screen (top right corner). All data are saved as meta (like before).', 'jm-tc' ) );
		}

		echo Utilities::brand_new( sprintf( __( '10.0.0 : Please see the new <a href="%s">Tutorial page</a> which can help you.', 'jm-tc' ), add_query_arg( 'page', 'jm_tc_tutorials', admin_url( 'admin.php' ) ) ) );

		$this->settings_api->show_forms();
		echo '</div>';
	}

	/**
	 * Process a settings export that generates a .json file of the shop settings
	 * @since 5.3.2
	 */
	public function process_settings_export() {

		if ( empty( $_POST['action'] ) || 'export_settings' !== $_POST['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['export_nonce'], 'export_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = [
			'tc' => (array) get_option( JM_TC_SLUG_MAIN_OPTION ),
			'ie' => (array) get_option( JM_TC_SLUG_CPT_OPTION ),
		];

		ignore_user_abort( true );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=jm-twitter-cards-settings-export-' . strtotime( 'now' ) . '.json' );
		header( 'Expires: 0' );

		echo json_encode( $settings );
		exit;
	}

	/**
	 * Process a settings import from a json file
	 * @since 5.3.2
	 */
	public function process_settings_import() {

		if ( empty( $_POST['action'] ) || 'import_settings' !== $_POST['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['import_nonce'], 'import_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );

		if ( 'json' !== $extension ) {
			wp_die( __( 'Please upload a valid .json file', 'jm-tc' ) );
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import', 'jm-tc' ) );
		}

		/**
		 * array associative
		 *
		 */
		$settings = (array) json_decode( file_get_contents( $import_file ), true );

		if ( ! empty( $settings['tc'] ) ) {
			update_option( JM_TC_SLUG_MAIN_OPTION, (array) $settings['tc'] );
		}
		if ( ! empty( $settings['ie'] ) ) {
			update_option( JM_TC_SLUG_CPT_OPTION, (array) $settings['ie'] );
		}

		wp_safe_redirect( add_query_arg( 'page', JM_TC_SLUG_MAIN_OPTION, admin_url( 'admin.php' ) ) );
		exit;

	}
}