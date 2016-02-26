<?php
namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Factory {
	/**
	 * Markup front
	 *
	 * @param bool|false $post_ID
	 * @author Julien Maury
	 * @return \TokenToMe\TwitterCards\Markup
	 */
	public function createMarkup( $post_ID = false ) {
		$options = new Admin\Options( $post_ID );
		echo new Markup( $options );
	}

	/**
	 * @author Julien Maury
	 * @return \TokenToMe\TwitterCards\Admin\Fields
	 */
	public function createFields(){
		return new Admin\Fields();
	}

	/**
	 * Preview metabox
	 *
	 * @param $post_ID
	 * @author Julien Maury
	 * @return \TokenToMe\TwitterCards\Admin\Preview
	 */
	public function createPreview( $post_ID ){
		$options = new Admin\Options( $post_ID );
		echo new Admin\Preview( $options );
	}
}