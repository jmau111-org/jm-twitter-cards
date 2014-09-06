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

			protected $opts;

			public function __construct(){

				add_filter( 'robots_txt', array( $this, 'robots_mod' ), 10, 2 );
				add_filter( 'cmb_meta_box_url', array( $this, 'update_cmb_meta_box_url' ) );

				$this->opts = jm_tc_get_options(); 

				if( isset( $this->opts['twitterCardExcerpt']  ) && $this->opts['twitterCardExcerpt'] == 'yes' )
					add_filter('jm_tc_get_excerpt', array( $this, 'modify_excerpt' ) );

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

				$opts = jm_tc_get_options();
				
				if( $opts['twitterCardRobotsTxt'] == 'yes' ) {
					$output .= "User-agent: Twitterbot" ."\n";
					$output .= "Disallow: ";
				}
				
				return $output;
			}

			// Whether or not include custom excerpt as meta desc
			function modify_excerpt() {
			    global $post;
			    return $this->get_excerpt_from_far_far_away( $post->ID );
			}

			function get_excerpt_from_far_far_away( $post_id )
			{
			    global $wpdb;
			    $query = 'SELECT post_excerpt FROM '. $wpdb->posts .' WHERE ID = %d LIMIT 1';
			    $result = $wpdb->get_results( $wpdb->prepare( $query, $post_id ), ARRAY_A );
			    $post_excerpt = $result[0]['post_excerpt'];
			    return esc_attr( $post_excerpt );
			}
		}

	}