<?php
namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$sections[] = array(
	'id'    => 'jm_tc',
	'title' => __( 'Options', 'jm-tc' )
);

$sections[] = array(
	'id'    => 'jm_tc_cpt',
	'title' => __( 'Post types' )
);
