<?php

namespace TokenToMe\TwitterCards;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Rest {

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

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * @param $auth
	 *
	 * @return bool|int
	 */
	public function rest_authentication_errors( $auth ) {
		if ( ! empty( $auth ) ) {
			return $auth;
		}

		if ( ! isset( $_SERVER['HTTP_TC_NONCE'] ) ) {
			return $auth;
		}

		$nonce = sanitize_text_field( $_SERVER['HTTP_TC_NONCE'] );
		return wp_verify_nonce( $nonce, 'tc_rest' );
	}

	public function gutenberg_register_meta() {

		foreach ( Utils::get_post_types() as $cpt ) {
			register_meta(
				$cpt, 'twitterCardType', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardImage', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardImageAlt', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayer', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayerWidth', [
					'type'         => 'int',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayerHeight', [
					'type'         => 'int',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayerStream', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayerCodec', [
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				]
			);
		}
	}

	public function process_meta( \WP_REST_Request $request ) {

	}

	public function gutenberg_api_post_meta() {
		register_rest_route(
			'jm-tc/v1', '/update-meta', [
				'methods'  => 'POST',
				'callback' => [ $this, 'gutenberg_update_callback' ],
				'args'     => [ $this, 'process_meta' ],
			]
		);
	}

}