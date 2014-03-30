<?php
//https://github.com/trepmal/featured-image-sizes/blob/master/featured-image-sizes.php

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Thumbs') ) {
	class JM_TC_Thumbs {

		var $textdomain = 'jm-tc';

		function __construct() {

			$possible_post_types = JM_TC_Utilities::get_post_types_that_support('thumbnail');

			$this->allowed_post_types = apply_filters( 'fis_post_types', $possible_post_types );

			add_filter( 'admin_post_thumbnail_html', array( &$this, 'admin_post_thumbnail_html' ), 10, 2 );
			add_action( 'save_post', array( &$this, 'save_post' ), 10, 2 );
			add_action('init', array($this,'thumbnail_create') );
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
			$opts = get_option('jm_tc_options');
			global $post;
			$twitterCardCancel 	= get_post_meta($post->ID, 'twitterCardCancel', true);
			$size				= ('' != ( $thumbnail_size = get_post_meta($post->ID, 'cardImgSize', true) ) && $twitterCardCancel != 'yes') ? $thumbnail_size : $opts['twitterCardImageSize'];

			switch ($size)
			{
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
			}

			return $twitterCardImgSize;
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
		
	

		function admin_post_thumbnail_html( $content, $post_id ) {
			
			$twitter_image_size = static::thumbnail_sizes();

			$post_type = get_post_type( $post_id );
			if ( ! isset( $this->allowed_post_types[ $post_type ] ) ) return $content;

			ob_start();
			wp_nonce_field( 'na-fis-image', 'nn-fis-image' );
			$saved_value = get_post_meta( $post_id, 'cardImgSize', true );

			echo '<label>';
			_e( 'Define specific size for twitter:image display',  $this->textdomain);
			echo '<select name="cardImgSize">';
			echo '<option value="'.$twitter_image_size.'">'.$twitter_image_size.'</option>';

			foreach( get_intermediate_image_sizes() as $size ) {
				$selected = selected( $size, $saved_value, false );
				echo "<option value='$size'$selected>$size</option>";
			}
			echo '</select></label>';

			$html = ob_get_clean();

			return $content . $html;
		}

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
			$ok_sizes = get_intermediate_image_sizes();
			if ( ! in_array( $fis_size, $ok_sizes ) ) return;

			update_post_meta( $post_id, 'cardImgSize', $fis_size );

		}

	}
}