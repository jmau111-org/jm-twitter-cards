<?php
//http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/media.php#L587

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Thumbs') ) {
	class JM_TC_Thumbs {

		var $textdomain = 'jm-tc';

		function __construct() {

			add_filter( 'admin_post_thumbnail_html', array( &$this, 'admin_post_thumbnail_html' ), 1, 2 );
			add_action( 'save_post', array( &$this, 'save_post' ), 10, 2 );
			add_action( 'init', array($this,'thumbnail_create') );
		}
		
		

		// Add stuffs in init such as img size
		function thumbnail_create()
		{
			$opts = get_option('jm_tc_options');
			$crop = ($opts['twitterCardCrop'] == 'yes') ? true : false;

			if (function_exists('add_theme_support')) add_theme_support('post-thumbnails');
			
			
			add_image_size('jmtc-small-thumb', 280, 150, $crop);
			/* the minimum size possible for Twitter Cards */
			add_image_size('jmtc-max-web-thumb', 435, 375, $crop);
			/* maximum web size for photo cards */
			add_image_size('jmtc-max-mobile-non-retina-thumb', 280, 375, $crop);
			/* maximum non retina mobile size for photo cards  */
			add_image_size('jmtc-max-mobile-retina-thumb', 560, 750, $crop);
			/* maximum retina mobile size for photo cards  */
		}

		public static function thumbnail_sizes()
		{
			
			global $post;
			
			$opts 				= get_option('jm_tc_options');
			$twitterCardCancel 	= get_post_meta($post->ID, 'twitterCardCancel', true);
			$size				=  ('' != ( $thumbnail_size = get_post_meta($post->ID, 'cardImgSize', true) ) && $twitterCardCancel != 'yes' && !is_home() && !is_front_page() ) ? $thumbnail_size : $opts['twitterCardImageSize'];

			return $size;
			
		}

		// get featured image
		public static function get_post_thumbnail_size()
		{
			global $post;
			$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => array(
			'image/png',
			'image/jpeg',
			'image/gif'
			) ,
			'numberposts' => - 1,
			'post_status' => null,
			'post_parent' => $post->ID
			);
			$attachments = get_posts($args);
			foreach($attachments as $attachment)
			{
				$math = filesize(get_attached_file($attachment->ID)) / 1000000;
				return $math; //Am I bold enough to call it a math?
			}
		}

		// Get list of thumb sizes		 
		function admin_post_thumbnail_html( $content, $post_id ) {
			
			$twitter_image_size = static::thumbnail_sizes();

			ob_start();
			wp_nonce_field( 'na-fis-image', 'nn-fis-image' );
			$saved_value = get_post_meta( $post_id, 'cardImgSize', true );

			echo '<label for="cardImgSize">';
			_e( 'Define specific size for twitter:image display',  $this->textdomain);
			echo '</label>';
			echo '<select id="cardImgSize" name="cardImgSize">';
			echo '<option value="'.$twitter_image_size.'">'.$twitter_image_size.'</option>';

			//we just need that 4 values in fact ^^
			$image_sizes = array('jmtc-small-thumb', 'jmtc-max-web-thumb', 'jmtc-max-mobile-non-retina-thumb', 'jmtc-max-mobile-retina-thumb'); // twitter card img sizes
			
			//oh wait we do not want this twice !!! 
			$double = array_search( $twitter_image_size, $image_sizes );
			unset( $image_sizes[$double] );
			
			
			
			foreach( $image_sizes as $size ) {
				$selected = selected( $size, $saved_value, false );
				echo "<option value='$size'$selected>$size</option>";
			}
			echo '</select>';

			$html = ob_get_clean();

			return $content . $html;
		}
		
		
		// Save value
		function save_post( $post_id, $post ) {

			if ( ! isset( $_POST['nn-fis-image'] ) ) //make sure our custom value is being sent
			return;
			if ( ! wp_verify_nonce( $_POST['nn-fis-image'], 'na-fis-image' ) ) //verify intent
			return;
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) //no auto saving
			return;
			if ( ! current_user_can( 'edit_post', $post_id ) ) //verify permissions
			return;

			$fis_size = trim( $_POST['cardImgSize'] );
			if ( empty( $fis_size ) ) {
				delete_post_meta( $post_id, 'cardImgSize' );
				return;
			}

			// make sure the POSTed value is an okay size name
			$ok_sizes = array('jmtc-small-thumb', 'jmtc-max-web-thumb', 'jmtc-max-mobile-non-retina-thumb', 'jmtc-max-mobile-retina-thumb');
			if ( ! in_array( $fis_size, $ok_sizes ) ) return;

			update_post_meta( $post_id, 'cardImgSize', $fis_size );

		}

	}
}