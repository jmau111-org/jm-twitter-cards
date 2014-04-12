<?php
//http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/media.php#L587

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists('JM_TC_Utilities') ) {

	class JM_TC_Thumbs extends JM_TC_Utilities
	{
	
		public function thumbnail_sizes($post_id)
		{
			$opts = get_option('jm_tc');
			
			$size = ('' != ( $thumbnail_size = get_post_meta($post->ID, 'cardImgSize', true) ) ) ? $thumbnail_size : $opts['twitterCardImgSize'];

			switch ($size) :
				case 'small':
					$twitterCardImgSize = 'jmtc-small-thumb';
					break;

				case 'web':
					$twitterCardImgSize = 'jmtc-max-web-thumb';
					break;

				case 'mobile-non-retina':
					$twitterCardImgSize = 'jmtc-max-mobile-non-retina-thumb';
					break;

				case 'mobile-retina':
					$twitterCardImgSize = 'jmtc-max-mobile-retina-thumb';
					break;

				default:
					$twitterCardImgSize = 'jmtc-small-thumb';
			?><!-- @(-_-)] --><?php
					break;
			endswitch;

			return $twitterCardImgSize;
		}
				
		

		// get featured image
		public function get_post_thumbnail_weight($post_id)
		{

				
			$math = filesize( get_attached_file( get_post_thumbnail_id( $post_id ) ) ) / 1000000;
			
		
			if( $math == 0 ) {
			
				$weight = __('No featured image for now !', 'jm-tc' );
			
			} elseif( $math > 1 ) {
				
				$weight  =  '<span class="error">'.__('Image is heavier than 1MB ! Card will be broken !', 'jm-tc' ).'</span>';
			
			} elseif( $math > 0 && $math < 1 ) {
				
				$weight = $math.' MB';	

			} else {
			
				$weight = __('Unknown error !', 'jm-tc' );
			}
			
			return $weight;

		}


	}
}