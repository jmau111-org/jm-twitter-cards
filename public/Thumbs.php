<?php

namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

use TokenToMe\TwitterCards\Utils as Utilities;

class Thumbs {

	protected $plugin_name;
	protected $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	static function thumbnail_sizes() {

		$opts = \jm_tc_get_options();
		$size = Utilities::maybe_get_opt( $opts, 'twitterCardImgSize' );

		switch ( $size ) {
			case 'small':
				return 'jmtc-small-thumb';
				break;
			case 'web':
				return 'jmtc-max-web-thumb';
				break;
			case 'mobile-non-retina':
				return 'jmtc-max-mobile-non-retina-thumb';
				break;
			case 'mobile-retina':
				return 'jmtc-max-mobile-retina-thumb';
				break;
			default:
				return 'jmtc-small-thumb';
		}

	}

	function add_featured_image_instruction( $content ) {
		return $content . self::get_post_thumbnail_size( get_queried_object_id() );
	}

	/**
	 * Get post thumb weight
	 * @return string
	 *
	 * @param integer $post_id
	 */
	static function get_post_thumbnail_size( $post_id ) {
		if ( 'attachment' === get_post_type( $post_id ) ) {
			return false;
		}

		if ( ! has_post_thumbnail( $post_id ) ) {

			return __( 'No featured image for now !', 'jm-tc' );
		}

		$file      = get_attached_file( get_post_thumbnail_id( $post_id ) );
		$file_size = filesize( $file );
		$math      = round( $file_size / 1048.576, 2 );

		// If that does not match the following case then it's weird
		$weight = __( 'Unknown error !', 'jm-tc' );

		if ( $math > 0 && $math < 1000 ) {

			$weight = sprintf( '%f kB', $math );// obviously the result will be in kB

		} elseif ( $math > 1000 ) {

			$weight = '<span class="error">' . __( 'Image is heavier than 1MB ! Card will be broken !', 'jm-tc' ) . '</span>';

		}

		return $weight;

	}

	function add_image_sizes() {

		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}

		$opts = \jm_tc_get_options();

		if ( empty( $opts['twitterCardImgSize'] ) ) {
			return false;
		}

		switch ( $opts['twitterCardImgSize'] ) {
			case 'small':
				add_image_size(
					'jmtc-small-thumb',
					280,
					150
				);/* the minimum size possible for Twitter Cards */
				break;

			case 'web':
				add_image_size(
					'jmtc-max-web-thumb',
					435,
					375
				);/* maximum web size for photo cards */
				break;

			case 'mobile-non-retina':
				add_image_size(
					'jmtc-max-mobile-non-retina-thumb',
					280,
					375
				);/* maximum non retina mobile size for photo cards*/
				break;

			case 'mobile-retina':
				add_image_size(
					'jmtc-max-mobile-retina-thumb',
					560,
					750
				);/* maximum retina mobile size for photo cards  */
				break;

			default:
				add_image_size(
					'jmtc-small-thumb',
					280,
					150
				);/* the minimum size possible for Twitter Cards */
		}
	}

}