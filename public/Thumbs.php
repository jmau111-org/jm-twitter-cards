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

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
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

}