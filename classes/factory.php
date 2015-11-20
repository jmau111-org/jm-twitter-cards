<?php
namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class MarkupFactory {
	public $post_ID;
	public function createMarkup( $post_ID = false ) {
		$options = new Admin\Options( $post_ID );

		return new Markup( $options );
	}
}