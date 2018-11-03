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
                $cpt, 'cardImageID', [
                    'type'         => 'integer',
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
					'type'         => 'integer',
					'single'       => true,
					'show_in_rest' => true,
				]
			);

			register_meta(
				$cpt, 'cardPlayerHeight', [
					'type'         => 'integer',
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

}