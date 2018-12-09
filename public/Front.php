<?php

namespace TokenToMe\TwitterCards;

use TokenToMe\TwitterCards\Admin\Options;
use TokenToMe\TwitterCards\Utils as Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Front {

	/**
	 * Options
	 * @var array
	 */
	protected $opts = [];
	protected $options = [];


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
	 * Add specific markup
	 */
	public function add_markup() {

		if ( ! is_home() && ! is_front_page() && ! in_array( get_post_type(), Utilities::get_post_types(), true ) ) {
			return false;
		}

		if ( ! is_404() && ! is_tag() && ! is_archive() && ! is_tax() && ! is_category() ) {
			$this->generate_markup();
		}

		return true;
	}

	public function generate_markup() {

		$this->options = new Options( get_queried_object_id() );
		$this->opts    = \jm_tc_get_options();

		echo $this->html_comments();

		/* most important meta */

		if ( is_front_page() || is_home() ) {
			echo $this->build( [ 'card' => Utils::maybe_get_opt( $this->opts, 'twitterCardType' ) ] );
			echo $this->build( [ 'creator' => Utils::maybe_get_opt( $this->opts, 'twitterCreator' ) ] );
			echo $this->build( [ 'site' => Utils::maybe_get_opt( $this->opts, 'twitterSite' ) ] );
			echo $this->build( [ 'title' => Utils::maybe_get_opt( $this->opts, 'twitterPostPageTitle' ) ] );
			echo $this->build( [ 'description' => Utils::maybe_get_opt( $this->opts, 'twitterPostPageDesc' ) ] );
			echo $this->build( [ 'image' => Utils::maybe_get_opt( $this->opts, 'twitterImage' ) ] );
			echo $this->build( [ 'image:alt' => Utils::maybe_get_opt( $this->opts, 'twitterImageAlt' ) ] );
		} else {
			echo $this->build( $this->options->card_type() );
			echo $this->build( $this->options->creator_username( true ) );
			echo $this->build( $this->options->site_username() );
			echo $this->build( $this->options->title() );
			echo $this->build( $this->options->description() );
			echo $this->build( $this->options->image() );
			echo $this->build( $this->options->image_alt() );
			echo $this->build( $this->options->player() );
		}

		echo $this->build( $this->options->deep_linking() );
		echo $this->html_comments( true );
	}

	/**
	 * Add just one line before meta
	 * @since 5.3.2
	 *
	 * @param bool $end
	 *
	 * @return string
	 */
	public function html_comments( $end = false ) {
		$slash = ( false === $end ) ? '' : '/';

		return '<!-- ' . $slash . 'JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . PHP_EOL;
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function build( $data ) {

		$markup = '';

		if ( is_array( $data ) ) {

			/**
			 * Values are filterable
			 * so we need to sanitize again
			 */
			$data = array_map( 'esc_attr', $data );
			$data = array_filter( $data );

			foreach ( $data as $name => $value ) {
				if ( '' !== $value ) {

					if ( '@' === $value ) {
						$markup = sprintf( '<!-- [(-_-)@ %s @(-_-)] -->', __( 'Missing critical option !', 'jm-tc' ) ) . PHP_EOL;
						continue;
					}

					if ( ! empty( $this->opts['twitterCardOg'] ) && 'yes' === $this->opts['twitterCardOg'] && in_array( $name, [
							'title',
							'description',
							'image',
						] )
					) {
						$is_og    = 'og';
						$name_tag = 'property';
					} else {
						$is_og    = 'twitter';
						$name_tag = 'name';
					}
					$markup .= '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
				}
			}
		} elseif ( is_string( $data ) ) {
			$markup .= '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . PHP_EOL;
		}

		return $markup;
	}
}