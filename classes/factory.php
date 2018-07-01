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
	 * @param $post_ID
	 *
	 * @author Julien Maury
	 */
	public function generateMarkup( $post_ID ) {
		$options = new Admin\Options( $post_ID );
		$markup  = new Markup( $options );
		$markup->generateMarkup();
	}


	/**
	 * @param $data
	 */
	public function generateMetaBox( $data ) {
		$fields = new Admin\Fields();
		$fields->generateFields( $data );
	}

	/**
	 * Preview metabox
	 *
	 * @param $post_ID
	 *
	 * @author Julien Maury
	 * @return \TokenToMe\TwitterCards\Admin\Preview
	 */
	public function generatePreview( $post_ID ) {
		$options = new Admin\Options( $post_ID );
		echo new Admin\Preview( $options );
	}
}