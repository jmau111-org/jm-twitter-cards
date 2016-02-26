<?php
namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


interface MetaBox {
	/**
	 * @param $post_type
	 * @param $post
	 *
	 * @return mixed
	 */
	public function add_box($post_type, $post);

	public function display_box();

	/**
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @return mixed
	 */
	public function save_box( $post_id, $post, $update );
}