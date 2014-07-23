<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

// SSL stuffs
add_filter( 'cmb_meta_box_url', 'jm_tc_update_cmb_meta_box_url' );
function jm_tc_update_cmb_meta_box_url( $url ) {
	if( is_ssl() ) {
    	// modify the url here
	    return preg_replace('|^http://|', 'https://', $url);
	} else {
		return $url;
	}
}


// Robots.txt with magic filter
add_filter( 'robots_txt', 'jm_tc_robots_mod', 10, 2 );
function jm_tc_robots_mod( $output, $public ) {

	$opts = get_option('jm_tc');
	
	if( $opts['twitterCardRobotsTxt'] == 'yes' ) {
		$output .= "User-agent: Twitterbot" ."\n";
		$output .= "Disallow: ";
	}
	
	return $output;
}