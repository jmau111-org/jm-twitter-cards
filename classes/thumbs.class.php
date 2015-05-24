<?php
namespace TokenToMe\twitter_cards;

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


class Thumbs{

	public function __construct(){

		add_action( 'init', array( __CLASS__, 'add_image_sizes' ) );
	}


	static function add_image_sizes(){

		/* Thumbnails */
		$opts    = jm_tc_get_options();
		$is_crop = true;
		$crop    = $opts['twitterCardCrop'];
		$crop_x  = $opts['twitterCardCropX'];
		$crop_y  = $opts['twitterCardCropY'];
		$size    = $opts['twitterCardImgSize'];

		switch ( $crop ) {
			case 'yes' :
				$is_crop = true;
				break;
			case 'no' :
				$is_crop = false;
				break;
			case 'yo' :
				global $wp_version;
				$is_crop = ( version_compare( $wp_version, '3.9', '>=' ) ) ? array( $crop_x, $crop_y ) : true;
				break;
		}

		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}

		switch ( $size ) {
			case 'small':
				add_image_size( 'jmtc-small-thumb', 280, 150, $is_crop );/* the minimum size possible for Twitter Cards */
				break;

			case 'web':
				add_image_size( 'jmtc-max-web-thumb', 435, 375, $is_crop );/* maximum web size for photo cards */
				break;

			case 'mobile-non-retina':
				add_image_size( 'jmtc-max-mobile-non-retina-thumb', 280, 375, $is_crop );/* maximum non retina mobile size for photo cards*/
				break;

			case 'mobile-retina':
				add_image_size( 'jmtc-max-mobile-retina-thumb', 560, 750, $is_crop );/* maximum retina mobile size for photo cards  */
				break;

			default:
				add_image_size( 'jmtc-small-thumb', 280, 150, $is_crop );/* the minimum size possible for Twitter Cards */
		}
	}

	/**
	 * @return string
	 */
	static function thumbnail_sizes(){

		$opts = jm_tc_get_options();
		$size = $opts['twitterCardImgSize'];

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
				return'jmtc-small-thumb';
		}

	}

	/**
	 * Get post thumb weight
	 * @return string
	 *
	 * @param integer $post_id
	 */
	static function get_post_thumbnail_size( $post_id ) {
		if ( 'attachment' === get_post_type( $post_id ) ) {
			return;
		}

		if ( ! has_post_thumbnail( $post_id ) ) {

			return __( 'No featured image for now !', JM_TC_TEXTDOMAIN );
		}

		$file = get_attached_file( get_post_thumbnail_id( $post_id ) );
		$file_size = filesize( $file );
		$math = round( $file_size / 1048.576, 2 );

		// If that does not match the following case then it's weird
		$weight = __( 'Unknown error !', JM_TC_TEXTDOMAIN );

		if ( $math > 0 && $math < 1000 ) {

			$weight = sprintf( '%f kB', $math );// obviously the result will be in kB

		} elseif ( $math > 1000 ) {

			$weight = '<span class="error">' . __( 'Image is heavier than 1MB ! Card will be broken !', JM_TC_TEXTDOMAIN ) . '</span>';

		}

		return $weight;

	}

}