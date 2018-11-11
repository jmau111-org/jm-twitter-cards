<?php

namespace TokenToMe\TwitterCards;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Meta {

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
	 * here there is no concerne about privacy links
	 * these are public meta displayed in <head>
	 */
	public function gutenberg_register_meta() {

		foreach ( Utils::get_post_types() as $cpt ) {

			register_meta(
				'post', 'twitterCardType', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);
			register_meta(
				'post', 'cardImageID', [
					'type'              => 'integer',
					'single'            => true,
					'show_in_rest'      => true,
					'object_subtype'    => $cpt,
					'sanitize_callback' => 'absint',
				]
			);

			register_meta(
				'post', 'cardImage', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);

			register_meta(
				'post', 'cardDesc', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);

			register_meta(
				'post', 'cardImageAlt', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);

			register_meta(
				'post', 'cardPlayer', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);

			register_meta(
				'post', 'cardPlayerWidth', [
					'type'              => 'integer',
					'single'            => true,
					'show_in_rest'      => true,
					'object_subtype'    => $cpt,
					'sanitize_callback' => 'absint',
				]
			);

			register_meta(
				'post', 'cardPlayerHeight', [
					'type'              => 'integer',
					'single'            => true,
					'show_in_rest'      => true,
					'object_subtype'    => $cpt,
					'sanitize_callback' => 'absint',
				]
			);

			register_meta(
				'post', 'cardPlayerStream', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);

			register_meta(
				'post', 'cardPlayerCodec', [
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'object_subtype' => $cpt,
				]
			);
		}

	}

}