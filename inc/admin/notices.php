<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists('JM_TC_Utilities') ) {

	class JM_TC_Notices extends JM_TC_Utilities{
	
	
		function __construct(){
			add_action('admin_init', array( &$this, 'ignore_this') );
			add_action('admin_notices', array( &$this, 'admin_notice') );		
		}

		// Add dismissible notice

		function admin_notice()
		{
			global $current_user;
			$user_id = $current_user->ID;

			// WP SEO Card Option

			if (!get_user_meta($user_id, 'ignore_notice') && current_user_can('install_plugins') && class_exists('WPSEO_Frontend'))
			{
				echo '<div class="error"><p>';
				printf(__('WordPress SEO by Yoast is activated, please uncheck Twitter Card option in this plugin if it is enabled to avoid adding markup twice | <a class="button" href="%1$s">Hide Notice</a>') , '?ignore_this=0', 'jm-tc');
				echo "</p></div>";
			}

		}
		

		function ignore_this()
		{
			global $current_user;
			$user_id = $current_user->ID;
			/* If user clicks to ignore the notice, add that to their user meta */
			if (isset($_GET['ignore_this']) && '0' == $_GET['ignore_this'])
			{
				add_user_meta($user_id, 'ignore_notice', 'true', true);
			}
		}	
	}
}