<?php
//http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/media.php#L587

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists('JM_TC_Utilities') ) {

	class JM_TC_Thumbs extends JM_TC_Utilities{

		var $textdomain = 'jm-tc';

		function thumbnail_sizes($post_id)
		{
			
			$size = get_post_meta($post_id, 'cardImgSize', true);


			switch ($size) :
				case 'small':
					$twitterCardImgSize = 'jm_tc_small';
					break;

				case 'web':
					$twitterCardImgSize = 'jm_tc_max_web';
					break;

				case 'mobile-non-retina':
					$twitterCardImgSize = 'jm_tc_max_mobile_non_retina';
					break;

				case 'mobile-retina':
					$twitterCardImgSize = 'jm_tc_max-mobile_retina';
					break;

				default:
					$twitterCardImgSize = 'jm_tc_small';
			?><!-- @(-_-)] --><?php
					break;
			endswitch;

			return $twitterCardImgSize;
		}
				
		

		// get featured image
		public static function get_post_thumbnail_weight($post_id)
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
			foreach($attachments as $attachment) $math = filesize( get_attached_file( $attachment->ID ) ) / 1000000;
			
			return $math = ( $math >= 1 ) ? '<span class="error">'.__('Image is heavier than 1MB ! Card will be broken !', $this->textdomain ).'</span>' : $math.' MB';
			
			
		}

	}
}