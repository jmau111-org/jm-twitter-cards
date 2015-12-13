<?php
namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Factory {
	/**
	 * @param bool|false $post_ID
	 *
	 * @return \TokenToMe\TwitterCards\Markup
	 */
	public function createMarkup( $post_ID = false ) {
		$options = new Admin\Options( $post_ID );
		echo new Markup( $options );
	}

	/**
	 * @param $post_ID
	 *
	 * @return \TokenToMe\TwitterCards\Admin\Preview
	 */
	public function createPreview( $post_ID ){
		$options = new Admin\Options( $post_ID );
		return new Admin\Preview( $options );
	}
}