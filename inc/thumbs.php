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
			add_action( 'init', array($this,'thumbnail_create') );
		}
		
		

		// Add stuffs in init such as img size
		function thumbnail_create()
		{
			$crop = ($this->opts['twitterCardCrop'] == 'yes') ? true : false;

			if (function_exists('add_theme_support')) 
				add_theme_support('postnails');
			
			
			add_image_size('jmtc-small', 280, 150, $crop);
			/* the minimum size possible for Twitter Cards */
			add_image_size('jmtc-max-web', 435, 375, $crop);
			/* maximum web size for photo cards */
			add_image_size('jmtc-max-mobile-non-retina', 280, 375, $crop);
			/* maximum non retina mobile size for photo cards  */
			add_image_size('jmtc-max-mobile-retina', 560, 750, $crop);
			/* maximum retina mobile size for photo cards  */
		}

		public static function get_thumbnail_sizes($post_id)
		{
			
			
			if ( '' != ( $thumbnail_size = get_post_meta($post_id, 'cardImgSize', true) ) && !is_home() && !is_front_page() ) {
			
				$size = $thumbnail_size;
			
			} else {
			
				$size = $this->opts['twitterCardImageSize'];
			 
			}	
			
			return $size;	
			
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