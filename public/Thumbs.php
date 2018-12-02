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
	protected $opts;
	protected static $sizes = [
		'small'             => [ 'w' => 280, 'h' => 150 ],
		'web'               => [ 'w' => 435, 'h' => 375 ],
		'mobile-non-retina' => [ 'w' => 280, 'h' => 375 ],
		'mobile-retina'     => [ 'w' => 560, 'h' => 750 ],
		'default'           => [ 'w' => 435, 'h' => 375 ],
	];

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * @return string
	 */
	static function thumbnail_size_names() {

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


	/**
	 * @param string $dimension
	 *
	 * @return array|int
	 */
	static function get_thumbnail_dimensions( $dimension = '' ) {

		$opts = \jm_tc_get_options();
		$size = Utilities::maybe_get_opt( $opts, 'twitterCardImgSize' );

		if ( empty( self::$sizes[ $size ] ) ) {
			return [];
		}

		$w = self::$sizes[ $size ]['w'];
		$h = self::$sizes[ $size ]['h'];

		if ( 'w' === $dimension ) {
			return $w;
		} elseif ( 'h' === $dimension ) {
			return $h;
		}

		return [ 'w' => $w, 'h' => $h ];
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
		$size = Utilities::maybe_get_opt( $opts, 'twitterCardImgSize' );

		if ( empty( $size ) ) {
			return false;
		}

		switch ( $size ) {
			case 'small':
				add_image_size(
					'jmtc-small-thumb',
					self::$sizes['small']['w'],
					self::$sizes['small']['h']
				);/* the minimum size possible for Twitter Cards */
				break;

			case 'web':
				add_image_size(
					'jmtc-max-web-thumb',
					self::$sizes['web']['w'],
					self::$sizes['web']['h']
				);/* maximum web size for photo cards */
				break;

			case 'mobile-non-retina':
				add_image_size(
					'jmtc-max-mobile-non-retina-thumb',
					self::$sizes['web']['w'],
					self::$sizes['web']['h']
				);/* maximum non retina mobile size for photo cards*/
				break;

			case 'mobile-retina':
				add_image_size(
					'jmtc-max-mobile-retina-thumb',
					self::$sizes['web']['w'],
					self::$sizes['web']['h']
				);/* maximum retina mobile size for photo cards  */
				break;

			default:
				add_image_size(
					'jmtc-max-web-thumb',
					self::$sizes['default']['w'],
					self::$sizes['default']['h']
				);/* the minimum size possible for Twitter Cards */
		}
	}

}