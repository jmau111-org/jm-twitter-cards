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
		
		
		//Add markup according to which page is displayed
		function add_markup() {
			
			/* get options */
			$opts = get_option('jm_tc_options');
			$open_tag  = '<meta name="twitter:';
			$close_tag =  '">' . "\n";
			
			global $post;

			$begin =  "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
			$end   =  '<!-- /JM Twitter Cards -->' . "\n\n";

			if ( is_home() || is_front_page() ) //detect post page or home (could be the same)
			{

				$output  = $begin;
				$output .= $open_tag.'card" content="' . $opts['twitterCardType'] . $close_tag;
				$output .= $open_tag.'creator" content="@' .static::remove_at($opts['twitterCardCreator']). $close_tag;
				$output .= $open_tag.'site" content="@' . static::remove_at($opts['twitterCardSite']). $close_tag;
				$output .= $open_tag.'title" content="' . $opts['twitterCardPostPageTitle'] . $close_tag;
				$output .= $open_tag.'description" content="' . $opts['twitterCardPostPageDesc'] . $close_tag;
				$output .= $open_tag.'image:src" content="' . $opts['twitterCardImage'] . $close_tag;
				
				//Deep linking
				if ($opts['twitterCardDeepLinking'] == 'yes') 
				{
					
					if( $opts['twitterCardiPhoneName'] != '' ) 		$output .= $open_tag.'app:name:iphone" content="' . $opts['twitterCardiPhoneName'] . $close_tag;
					if( $opts['twitterCardiPadName'] != '' )       	$output .= $open_tag.'app:name:ipad" content="' . $opts['twitterCardiPadName'] . $close_tag;
					if( $opts['twitterCardGooglePlayName'] != '' ) 	$output .= $open_tag.'app:name:googleplay" content="' . $opts['twitterCardGooglePlayName'] . $close_tag;
					if( $opts['twitterCardiPhoneUrl'] != '' ) 		$output .= $open_tag.'app:url:iphone" content="' . $opts['twitterCardiPhoneUrl'] .$close_tag;
					if( $opts['twitterCardiPadUrl'] != '' ) 		$output .= $open_tag.'app:url:ipad" content="' . $opts['twitterCardiPhoneUrl'] . $close_tag;
					if( $opts['twitterCardGooglePlayUrl'] != '' ) 	$output .= $open_tag.'app:url:googleplay" content="' . $opts['twitterCardGooglePlayUrl'] . $close_tag;
					if( $opts['twitterCardiPhoneId'] != '' ) 		$output .= $open_tag.'app:id:iphone" content="' . $opts['twitterCardiPhoneId'] . $close_tag;
					if( $opts['twitterCardiPadId'] != '' ) 			$output .= $open_tag.'app:id:ipad" content="' . $opts['twitterCardiPadId'] . $close_tag;
					if( $opts['twitterCardGooglePlayId'] != '' ) 	$output .= $open_tag.'app:id:googleplay" content="' . $opts['twitterCardGooglePlayId'] . $close_tag;
				}
				
				$output .= $end;
				
				echo apply_filters('jmtc_markup_home', $output); // provide filter for developers.

			}
			
			if( is_singular() && !is_front_page() && !is_home() && !is_404() && !is_tag() ) // avoid pages that do not need cards
			{

				
				$creator 			= get_the_author_meta('jm_tc_twitter', $post->post_author);
				
				
				
				$cardType 			= get_post_meta($post->ID, 'twitterCardType', true);
				$cardPhotoWidth	    = get_post_meta($post->ID, 'cardPhotoWidth', true);
				$cardPhotoHeight 	= get_post_meta($post->ID, 'cardPhotoHeight', true);
				$cardProductWidth 	= get_post_meta($post->ID, 'cardProductWidth', true);
				$cardProductHeight 	= get_post_meta($post->ID, 'cardProductHeight', true);
				$cardPlayerWidth 	= get_post_meta($post->ID, 'cardPlayerWidth', true);
				$cardPlayerHeight 	= get_post_meta($post->ID, 'cardPlayerHeight', true);
				$cardPlayer 		= get_post_meta($post->ID, 'cardPlayer', true);
				$cardPlayerStream	= get_post_meta($post->ID, 'cardPlayerStream', true);
				$cardImage 			= get_post_meta($post->ID, 'cardImage', true);
				$cardData1 			= get_post_meta($post->ID, 'cardData1', true);
				$cardLabel1		 	= get_post_meta($post->ID, 'cardLabel1', true);
				$cardData2 			= get_post_meta($post->ID, 'cardData2', true);
				$cardLabel2 		= get_post_meta($post->ID, 'cardLabel2', true);
				$cardImgSize 		= get_post_meta($post->ID, 'cardImgSize', true);
				$twitterCardCancel 	= get_post_meta($post->ID, 'twitterCardCancel', true);
				
				
				
				
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
				
				

				if (($opts['twitterCardMetabox'] == 'yes') && $cardType != '' && $twitterCardCancel != 'yes')
				{
					$output .= $open_tag.'card" content="' . apply_filters('jm_tc_card_type', $cardType ). $close_tag;
				}
				else
				{
					$output .= $open_tag.'card" content="' . apply_filters('jm_tc_card_type', $opts['twitterCardType'] ). $close_tag;
				}

				if ($opts['twitterCardProfile'] == 'yes' && $creator != '' )
				{ // this part has to be optional, this is more for guest bltwitterging but it's no reason to bother everybody.
					$output .= $open_tag.'creator" content="@' . $creator . $close_tag;
				}
				elseif ($opts['twitterCardProfile'] == 'no' && $username != '' && !is_array($username))
				{ // http://codex.wordpress.org/Function_Reference/get_user_meta#Return_Values
					$output .= $open_tag.'creator" content="@' . static::remove_at($username) . $close_tag;
				}
				else
				{
					$output .= $open_tag.'creator" content="@' . static::remove_at($opts['twitterCardCreator']) . $close_tag;
				}

				// these next 4 parameters should not be editable in post admin

				$output .= $open_tag.'site" content="@' . static::remove_at($opts['twitterCardSite']) . $close_tag;
				$output .= $open_tag.'title" content="' . $cardTitle . $close_tag; // filter used by plugin to customize title
				$output .= $open_tag.'description" content="' . static::remove_lb($cardDescription) . $close_tag;
				
				
				//gallery
				if ($cardType != 'gallery')
				{
					if (get_the_post_thumbnail($post->ID) != '')
					{
						if ($cardImage != '' && $twitterCardCancel != 'yes')
						{ // cardImage is set
							$output .= $open_tag.'image:src" content="' .  apply_filters( 'jm_tc_image_source', $cardImage ). $close_tag;
						}
						else
						{
							$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , static::thumbnail_sizes());
							$output .= $open_tag.'image:src" content="' . apply_filters( 'jm_tc_image_source', $image_attributes[0] ) . $close_tag;
						}
					}
					elseif (get_the_post_thumbnail($post->ID) == '' && $cardImage != '' && $twitterCardCancel != 'yes')
					{
						$output .=  $open_tag.'image:src" content="' . apply_filters( 'jm_tc_image_source', $cardImage ) . $close_tag;
					}
					
					elseif ( 'attachment' == get_post_type() ) 
					{
						
						$output .= $open_tag.'image:src" content="' . apply_filters( 'jm_tc_image_source', wp_get_attachment_url( $post->ID ) ) . $close_tag;
						
					}
					
					else
					{ //fallback
						$output .= $open_tag.'image:src" content="' . apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ). $close_tag;
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
									$output .= $open_tag.'image' . $i . '" content="' . apply_filters( 'jm_tc_image_source', $pic ). $close_tag;
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
							$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , static::thumbnail_sizes() );
							$output .= $open_tag.'image:src" content="' . apply_filters( 'jm_tc_image_source',$image_attributes[0] ). $close_tag;
						}
						else
						{
							$output .= $open_tag.'image:src" content="' .apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ). $close_tag;
						}
					}
				}
				
				//photo
				if ($opts['twitterCardType'] == 'photo' || $cardType == 'photo'  )
				{
					if ( $cardPhotoWidth != '' && $cardPhotoHeight != '' && $twitterCardCancel != 'yes' )
					{
						$output .= $open_tag.'image:width" content="' . $cardPhotoWidth . $close_tag;
						$output .= $open_tag.'image:height" content="' . $cardPhotoHeight . $close_tag;
					}
					elseif ($opts['twitterCardType'] == 'photo' && $twitterCardCancel != 'yes' && $opts['twitterCardMetabox'] != 'yes')
					{
						$output .= $open_tag.'image:width" content="' . $opts['twitterCardImageWidth'] . $close_tag;
						$output .= $open_tag.'image:height" content="' . $opts['twitterCardImageHeight'] . $close_tag;
					}
				}
				
				//product
				if ($cardType == 'product' && $twitterCardCancel != 'yes')
				{
					if ( $cardData1 != '' &&  $cardLabel1 != '' && $cardData2 != '' && $cardLabel2 != '' )
					{
						$output .= $open_tag.'data1" content="' . $cardData1 . $close_tag;
						$output .= $open_tag.'label1" content="' . $cardLabel1 . $close_tag;
						$output .= $open_tag.'data2" content="' . $cardData2 . $close_tag;
						$output .= $open_tag.'label2" content="' . $cardLabel2 . $close_tag;
					}
					else
					{
						$output .=   '<!-- ' .__('Warning : Product Card is not set properly ! There is no product datas !', 'jm-tc').'@(-_-)] -->' . "\n";
					}

					if ( $cardProductWidth != '' && $cardProductHeight != '' && $cardType == 'product')
					{
						$output .= $open_tag.'image:width" content="' . $cardProductWidth . $close_tag;
						$output .= $open_tag.'image:height" content="' . $cardProductHeight . $close_tag;
					}
					else
					{
						$output .= $open_tag.'image:width" content="' . $opts['twitterCardImageWidth'] . $close_tag;
						$output .= $open_tag.'image:height" content="' . $opts['twitterCardImageHeight'] . $close_tag;
					}
				}
				
				
				if ($cardType == 'player' && $twitterCardCancel != 'yes' )
				{
					if ( $cardPlayer != '' ) {
						$output .= $open_tag.'player" content="' . $cardPlayer . $close_tag;
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
						$output .= $open_tag.'player:width" content="' . $cardPlayerWidth . $close_tag;
						$output .= $open_tag.'player:height" content="' . $cardPlayerHeight . $close_tag;
					}
					else 
					{
						$output .= $open_tag.'player:width" content="435"'. $close_tag;
						$output .= $open_tag.'player:height" content="251"'. $close_tag;		
					}
					
					//Player stream
					if ( $cardPlayerStream != '' ) 
					{
						$output .= $open_tag.'player:stream" content="'.$cardPlayerStream.$close_tag;
						$output .= $open_tag.'player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;'.$close_tag;
					}
					
					if ( $cardPlayerStream != '' && !preg_match( $regex,$cardPlayerStream ) )
					{
						$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of raw stream Player MUST BE https!', 'jm-tc') . "\n";
					}
					
					
					//Deep linking
					if ($opts['twitterCardDeepLinking'] == 'yes') 
					{
						
						if( $opts['twitteriPhoneName'] != '' ) 		$output .= $open_tag.'app:name:iphone" content="' . $opts['twitteriPhoneName'] . $close_tag;
						if( $opts['twitteriPadName'] != '' ) 		$output .= $open_tag.'app:name:ipad" content="' . $opts['twitteriPadName'] . $close_tag;
						if( $opts['twitterGooglePlayName'] != '' ) 	$output .= $open_tag.'app:name:googleplay" content="' . $opts['twitterGooglePlayName'] . $close_tag;
						if( $opts['twitteriPhoneUrl'] != '' ) 		$output .= $open_tag.'app:url:iphone" content="' . $opts['twitteriPhoneUrl'] .$close_tag;
						if( $opts['twitteriPadUrl'] != '' ) 		$output .= $open_tag.'app:url:ipad" content="' . $opts['twitteriPhoneUrl'] . $close_tag;
						if( $opts['twitterGooglePlayUrl'] != '' )   $output .= $open_tag.'app:url:googleplay" content="' . $opts['twitterGooglePlayUrl'] . $close_tag;
						if( $opts['twitteriPhoneId'] != '' ) 		$output .= $open_tag.'app:id:iphone" content="' . $opts['twitteriPhoneId'] . $close_tag;
						if( $opts['twitteriPadId'] != '' ) 			$output .= $open_tag.'app:id:ipad" content="' . $opts['twitteriPadId'] . $close_tag;
						if( $opts['twitterGooglePlayId'] != '' ) 	$output .= $open_tag.'app:id:googleplay" content="' . $opts['twitterGooglePlayId'] . $close_tag;
					}
				}
				
				$output  .= $end;

				echo apply_filters('jmtc_markup', $output); // provide filter for developers.
				
			}	
		}	

	}

}
