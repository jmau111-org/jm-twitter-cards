<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


// For now it's not OOP it is hardly a draft
if( ! class_exists('JM_TC_Markup') ) {

	Class JM_TC_Markup extends JM_TC_Utilities {	

		function __construct() {
		
			add_action('wp_head', array(&$this, 'add_markup'), 2 );
			
		}

		
		public static function getMeta($name, $content)
		{	
			return '<meta name="twitter:'.$name.'" content="'.$content.'">' . "\n";
		}
		
		
		
		//Add markup according to which page is displayed
		function add_markup() {
			
			/* get options */
			$opts = get_option('jm_tc_options');
			
			global $post;

			$begin =  "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			$end   =  '<!-- /JM Twitter Cards -->' . "\n\n";

			if ( is_home() || is_front_page() ) //detect post page or home (could be the same)
			{

				$output  = $begin;
				$output .= static::getMeta('card',  $opts['twitterCardType'] );
				$output .= static::getMeta('creator', '@'.static::remove_at($opts['twitterCardCreator']));
				$output .= static::getMeta('site', '@' . static::remove_at($opts['twitterCardSite']));
				$output .= static::getMeta('title',  $opts['twitterCardPostPageTitle'] );
				$output .= static::getMeta('description',  $opts['twitterCardPostPageDesc'] );
				$output .= static::getMeta('image:src',  $opts['twitterCardImage'] );
				
				//Deep linking
				if ($opts['twitterCardDeepLinking'] == 'yes') 
				{
					
					if( $opts['twitterCardiPhoneName'] != '' ) 		$output .= static::getMeta('app:name:iphone',  $opts['twitterCardiPhoneName'] );
					if( $opts['twitterCardiPadName'] != '' )       	$output .= static::getMeta('app:name:ipad',  $opts['twitterCardiPadName'] );
					if( $opts['twitterCardGooglePlayName'] != '' ) 	$output .= static::getMeta('app:name:googleplay',  $opts['twitterCardGooglePlayName'] );
					if( $opts['twitterCardiPhoneUrl'] != '' ) 		$output .= static::getMeta('app:url:iphone',  $opts['twitterCardiPhoneUrl'] );
					if( $opts['twitterCardiPadUrl'] != '' ) 		$output .= static::getMeta('app:url:ipad',  $opts['twitterCardiPhoneUrl'] );
					if( $opts['twitterCardGooglePlayUrl'] != '' ) 	$output .= static::getMeta('app:url:googleplay',  $opts['twitterCardGooglePlayUrl'] );
					if( $opts['twitterCardiPhoneId'] != '' ) 		$output .= static::getMeta('app:id:iphone',  $opts['twitterCardiPhoneId'] );
					if( $opts['twitterCardiPadId'] != '' ) 			$output .= static::getMeta('app:id:ipad',  $opts['twitterCardiPadId'] );
					if( $opts['twitterCardGooglePlayId'] != '' ) 	$output .= static::getMeta('app:id:googleplay',  $opts['twitterCardGooglePlayId'] );
				}
				
				$output .= $end;
				
				echo apply_filters('jmtc_markup_home', $output); // provide filter for developers.

			}
			
			if( is_singular() && !is_front_page() && !is_home() && !is_404() && !is_tag() ) // avoid pages that do not need cards
			{

				
				$creator 			= get_the_author_meta('jm_tc_twitter', $post->post_author);
				$params 			= get_post_meta( $post->ID );
				
			
				$cardType 			= isset($params['twitterCardType']) ? $params['twitterCardType'][0] : '';
				$cardPhotoWidth	    = isset($params['cardPhotoWidth']) ? $params['cardPhotoWidth'][0] : '';
				$cardPhotoHeight 	= isset($params['cardPhotoHeight']) ? $params['cardPhotoHeight'][0] : '';
				$cardProductWidth 	= isset($params['cardProductWidth']) ? $params['cardProductWidth'][0] : '';
				$cardProductHeight 	= isset($params['cardProductHeight']) ? $params['cardProductHeight'][0] : '';
				$cardPlayerWidth 	= isset($params['cardPlayerWidth']) ? $params['cardPlayerWidth'][0] : '';
				$cardPlayerHeight 	= isset($params['cardPlayerHeight']) ? $params['cardPlayerHeight'][0] : '';
				$cardPlayer 		= isset($params['cardPlayer']) ? $params['cardPlayer'] : '';
				$cardPlayerStream	= isset($params['cardPlayerStream']) ? $params['cardPlayerStream'][0] : '';
				$cardImage 			= isset($params['cardImage']) ? $params['cardImage'][0] : '';
				$cardData1 			= isset($params['cardData1']) ? $params['cardData1'][0] : '';
				$cardLabel1		 	= isset($params['cardLabel1']) ? $params['cardLabel1'][0] : '';
				$cardData2 			= isset($params['cardData2']) ? $params['cardData2'][0] : '';
				$cardLabel2 		= isset($params['cardLabel2']) ? $params['cardLabel2'][0] : '';
				$cardImgSize 		= isset($params['cardImgSize']) ? $params['cardImgSize'][0] : '';
				$twitterCardCancel 	= isset($params['twitterCardCancel']) ? $params['twitterCardCancel'][0] : '';
				
				
				//regex for player cards
				$regex = '~(https://|www.)(.+?)~';

				// from option page
				$username	 		= get_user_meta(get_current_user_id() , $opts['twitterCardUsernameKey'], true);

				// support for custom meta description WordPress SEO by Yoast or All in One SEO

				if (class_exists('WPSEO_Frontend'))
				{ // little trick to check if plugin is here and active :)
					$object = new WPSEO_Frontend();
					if ($opts['twitterCardSEOTitle'] == 'yes' && $object->title(false))
					{
						$cardTitle = $object->title(false);
					}
					else
					{
						$cardTitle = the_title_attribute(array(
						'echo' => false
						));
					}

					if ($opts['twitterCardSEODesc'] == 'yes' && $object->metadesc(false))
					{
						$cardDescription = $object->metadesc(false);
					}
					else
					{
						$cardDescription = apply_filters('jm_tc_get_excerpt', static::get_excerpt_by_id($post->ID) );
					}
				}
				elseif (class_exists('All_in_One_SEO_Pack'))
				{
					global $post;
					$post_id = $post;
					if (is_object($post_id)) $post_id = $post_id->ID;
					if ($opts['twitterCardSEOTitle'] == 'yes' && get_post_meta(get_the_ID() , '_aioseop_title', true))
					{
						$cardTitle = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_aioseop_title', true)));
					}
					else
					{
						$cardTitle = the_title_attribute(array(
						'echo' => false
						));
					}

					if ($opts['twitterCardSEODesc'] == 'yes' && get_post_meta(get_the_ID() , '_aioseop_description', true))
					{
						$cardDescription = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_aioseop_description', true)));
					}
					else
					{
						$cardDescription = apply_filters('jm_tc_get_excerpt', static::get_excerpt_by_id($post->ID) );
					}
				}
				elseif (  function_exists('get_field') && $opts['twitterCardTitle'] != '' && $opts['twitterCardDesc'] != '' ) // we detect ACF with the function get_field()
				{

					// avoid array to string notice on title and desc
					$cardTitle 			= get_field( $opts['twitterCardTitle'], $post->ID );
					$cardDescription 	= get_field( $opts['twitterCardDesc'], $post->ID );
				}
				else
				{ //default (I'll probably make a switch next time)
					$cardTitle = the_title_attribute(array(
					'echo' => false
					));
					$cardDescription = apply_filters('jm_tc_get_excerpt', static::get_excerpt_by_id($post->ID) );
				}	
				
				
				$output  = $begin;
				
				

				if ( $cardType != '' && $twitterCardCancel != 'yes' )
				{
					$output .= static::getMeta('card',  apply_filters('jm_tc_card_type', $cardType ));
				}
				else
				{
					$output .= static::getMeta('card',  apply_filters('jm_tc_card_type', $opts['twitterCardType'] ));
				}

				if ( $creator != '' )
				{ // this part has to be optional, this is more for guest bltwitterging but it's no reason to bother everybody.
					$output .= static::getMeta('creator', '@' . $creator );
				}
				elseif ($opts['twitterCardProfile'] == 'no' && $username != '' && !is_array($username))
				{ // http://codex.wordpress.org/Function_Reference/get_user_meta#Return_Values
					$output .= static::getMeta('creator', '@' . static::remove_at($username) );
				}
				else
				{
					$output .= static::getMeta('creator', '@' . static::remove_at($opts['twitterCardCreator']) );
				}

				// these next 4 parameters should not be editable in post admin

				$output .= static::getMeta('site', '@' . static::remove_at($opts['twitterCardSite']) );
				$output .= static::getMeta('title',  $cardTitle ); // filter used by plugin to customize title
				$output .= static::getMeta('description',  static::remove_lb($cardDescription) );
				
				
				//gallery
				if ($cardType != 'gallery')
				{
					if (get_the_post_thumbnail($post->ID) != '')
					{
						if ($cardImage != '' && $twitterCardCancel != 'yes')
						{ // cardImage is set
							$output .= static::getMeta('image:src',   apply_filters( 'jm_tc_image_source', $cardImage ));
						}
						else
						{
							$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , JM_TC_Thumbs::thumbnail_sizes());
							$output .= static::getMeta('image:src',  apply_filters( 'jm_tc_image_source', $image_attributes[0] ) );
						}
					}
					elseif (get_the_post_thumbnail($post->ID) == '' && $cardImage != '' && $twitterCardCancel != 'yes')
					{
						$output .=  static::getMeta('image:src',  apply_filters( 'jm_tc_image_source', $cardImage ) );
					}
					
					elseif ( 'attachment' == get_post_type() ) 
					{
						
						$output .= static::getMeta('image:src',  apply_filters( 'jm_tc_image_source', wp_get_attachment_url( $post->ID ) ) );
						
					}
					
					else
					{ //fallback
						$output .= static::getMeta('image:src',  apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ));
					}
				}
				else
				{ // markup will be different
					if ($twitterCardCancel != 'yes')
					{
						if ( is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'gallery'))
						{

							// get attachment for gallery cards

							$args = array(
							'post_type' => 'attachment',
							'numberposts' => - 1,
							'exclude' => get_post_thumbnail_id() ,
							'post_mime_type' => 'image',
							'post_status' => null,
							'post_parent' => $post->ID
							);
							$attachments = get_posts($args);
							if ($attachments && count($attachments) > 3)
							{
								$i = 0;
								foreach($attachments as $attachment)
								{

									// get attachment array with the ID from the returned posts

									$pic = wp_get_attachment_url($attachment->ID);
									$output .= static::getMeta('image' . $i . '',  apply_filters( 'jm_tc_image_source', $pic ));
									$i++;
									if ($i > 3) break; //in case there are more than 4 images in post, we are not allowed to add more than 4 images in our card by Twitter
								}
							}
						}
						else
						{
							$output .=  '<!-- ' . __('Warning : Gallery Card is not set properly ! There is no gallery in this post !', 'jm-tc')  .'@(-_-)] -->' . "\n";
						}
					}
					else
					{
						if (has_post_thumbnail())
						{
							$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , JM_TC_Thumbs::thumbnail_sizes() );
							$output .= static::getMeta('image:src',  apply_filters( 'jm_tc_image_source',$image_attributes[0] ));
						}
						else
						{
							$output .= static::getMeta('image:src', apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ));
						}
					}
				}
				
				//photo
				if ($opts['twitterCardType'] == 'photo' || $cardType == 'photo'  )
				{
					if ( $cardPhotoWidth != '' && $cardPhotoHeight != '' && $twitterCardCancel != 'yes' )
					{
						$output .= static::getMeta('image:width',  $cardPhotoWidth );
						$output .= static::getMeta('image:height',  $cardPhotoHeight );
					}
					elseif ($opts['twitterCardType'] == 'photo' && $twitterCardCancel != 'yes' && $opts['twitterCardMetabox'] != 'yes')
					{
						$output .= static::getMeta('image:width',  $opts['twitterCardImageWidth'] );
						$output .= static::getMeta('image:height',  $opts['twitterCardImageHeight'] );
					}
				}
				
				//product
				if ($cardType == 'product' && $twitterCardCancel != 'yes')
				{
					if ( $cardData1 != '' &&  $cardLabel1 != '' && $cardData2 != '' && $cardLabel2 != '' )
					{
						$output .= static::getMeta('data1',  $cardData1 );
						$output .= static::getMeta('label1',  $cardLabel1 );
						$output .= static::getMeta('data2',  $cardData2 );
						$output .= static::getMeta('label2',  $cardLabel2 );
					}
					else
					{
						$output .=   '<!-- ' .__('Warning : Product Card is not set properly ! There is no product datas !', 'jm-tc').'@(-_-)] -->' . "\n";
					}

					if ( $cardProductWidth != '' && $cardProductHeight != '' && $cardType == 'product')
					{
						$output .= static::getMeta('image:width',  $cardProductWidth );
						$output .= static::getMeta('image:height',  $cardProductHeight );
					}
					else
					{
						$output .= static::getMeta('image:width',  $opts['twitterCardImageWidth'] );
						$output .= static::getMeta('image:height',  $opts['twitterCardImageHeight'] );
					}
				}
				
				
				if ($cardType == 'player' && $twitterCardCancel != 'yes' )
				{
					if ( $cardPlayer != '' ) {
						$output .= static::getMeta('player',  $cardPlayer );
					} 
					else
					{
						$output .=  '<!-- ' .__('Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', 'jm-tc') .'@(-_-)] -->' . "\n";					
					}
					
					if (  $cardPlayer != '' && !preg_match( $regex,$cardPlayer) )
					{
						$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of iFrame Player MUST BE https!', 'jm-tc') .'@(-_-)] -->' . "\n";
					}
					
					
					if ( $cardPlayerWidth != '' && $cardPlayerHeight != ''  )
					{
						$output .= static::getMeta('player:width',  $cardPlayerWidth );
						$output .= static::getMeta('player:height',  $cardPlayerHeight );
					}
					else 
					{
						$output .= static::getMeta('player:width" content="435"');
						$output .= static::getMeta('player:height" content="251"');		
					}
					
					//Player stream
					if ( $cardPlayerStream != '' ) 
					{
						$output .= static::getMeta('player:stream" content="'.$cardPlayerStream);
						$output .= static::getMeta('player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;');
					}
					
					if ( $cardPlayerStream != '' && !preg_match( $regex,$cardPlayerStream ) )
					{
						$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of raw stream Player MUST BE https!', 'jm-tc') . "\n";
					}
					
					
					//Deep linking
					if ($opts['twitterCardDeepLinking'] == 'yes') 
					{
						
						if( $opts['twitteriPhoneName'] != '' ) 		$output .= static::getMeta('app:name:iphone',  $opts['twitteriPhoneName'] );
						if( $opts['twitteriPadName'] != '' ) 		$output .= static::getMeta('app:name:ipad',  $opts['twitteriPadName'] );
						if( $opts['twitterGooglePlayName'] != '' ) 	$output .= static::getMeta('app:name:googleplay',  $opts['twitterGooglePlayName'] );
						if( $opts['twitteriPhoneUrl'] != '' ) 		$output .= static::getMeta('app:url:iphone',  $opts['twitteriPhoneUrl'] );
						if( $opts['twitteriPadUrl'] != '' ) 		$output .= static::getMeta('app:url:ipad',  $opts['twitteriPhoneUrl'] );
						if( $opts['twitterGooglePlayUrl'] != '' )   $output .= static::getMeta('app:url:googleplay',  $opts['twitterGooglePlayUrl'] );
						if( $opts['twitteriPhoneId'] != '' ) 		$output .= static::getMeta('app:id:iphone',  $opts['twitteriPhoneId'] );
						if( $opts['twitteriPadId'] != '' ) 			$output .= static::getMeta('app:id:ipad',  $opts['twitteriPadId'] );
						if( $opts['twitterGooglePlayId'] != '' ) 	$output .= static::getMeta('app:id:googleplay',  $opts['twitterGooglePlayId'] );
					}
				}
				
				$output  .= $end;

				echo apply_filters('jmtc_markup', $output); // provide filter for developers.
				
			}	
		}	

	}

}
