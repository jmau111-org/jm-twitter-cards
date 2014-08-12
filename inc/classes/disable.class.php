<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( ! class_exists('JM_TC_Disable') ) {

	class JM_TC_Disable {
	
		function __construct() {
		
			add_action( 'init', array( $this, 'disable_yoast_twitter' ) );
			//disable yoast markup but only twitter meta of course !
			
		}
		
		public function disable_yoast_twitter(){
		
			$opt = get_option( 'wpseo_social' );
			$opt['twitter'] = false;
			
			update_option('wpseo_social', $opt);
			
		}

	
	}
}