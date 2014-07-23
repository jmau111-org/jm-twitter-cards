<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( !class_exists('JM_TC_Particular') )
	{

	class JM_TC_Particular
		{

			public function __construct(){

				add_filter( 'robots_txt', array( $this, 'robots_mod' ), 10, 2 );
				add_filter( 'cmb_meta_box_url', array( $this, 'update_cmb_meta_box_url' ) );

			}

			// SSL stuffs
			public static function update_cmb_meta_box_url( $url ) {
				if( is_ssl() ) {
			    	// modify the url here
				    return preg_replace('|^http://|', 'https://', $url);
				} else {
					return $url;
				}
			}


			// Robots.txt with magic filter
			public static function robots_mod( $output, $public ) {

				$opts = get_option('jm_tc');
				
				if( $opts['twitterCardRobotsTxt'] == 'yes' ) {
					$output .= "User-agent: Twitterbot" ."\n";
					$output .= "Disallow: ";
				}
				
				return $output;
			}
		}

	}