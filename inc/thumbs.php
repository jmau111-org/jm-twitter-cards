<?php
//http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/media.php#L587

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Thumbs') ) {

	class JM_TC_Thumbs extends JM_TC_Utilities{
		
		var $opts;
		var $textdomain = 'jm-tc';

		function __construct() {
		
			$this->opts = get_option('jm_tc_options');
		}
		

		// get featured image
		public static function get_post_thumbnail_size($post_id)
		{
			$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => array(
			'image/png',
			'image/jpeg',
			'image/gif'
			) ,
			'numberposts' => - 1,
			'post_status' => null,
			'post_parent' => $post_id,
			);
			
			$attachments = get_posts($args);
			foreach($attachments as $attachment)
			{
				$math = filesize(get_attached_file($attachment->ID)) / 1000000;
				return $math; //Am I bold enough to call it a math?
			}
		}

	}
}