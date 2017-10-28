<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class ImportExport {
	/**
	 * Constructor
	 * @since 5.3.2
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'process_settings_export' ) );
		add_action( 'admin_init', array( $this, 'process_settings_import' ) );
	}

	/**
	 * Process a settings export that generates a .json file of the shop settings
	 * @since 5.3.2
	 */
	function process_settings_export() {

		if ( empty( $_POST['action'] ) || 'export_settings' !== $_POST['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['export_nonce'], 'export_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = array( 'tc' => (array) get_option( 'jm_tc' ), 'ie' => (array) get_option( 'jm_tc_cpt' ), );

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
	function process_settings_import() {

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
			update_option( 'jm_tc', (array) $settings['tc'] );
		}
		if ( ! empty( $settings['ie'] ) ) {
			update_option( 'jm_tc_cpt', (array) $settings['ie'] );
		}

		wp_safe_redirect( add_query_arg( 'page', 'jm_tc', admin_url( 'admin.php' ) ) );
		exit;

	}

}