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
				global $post;

				$begin =  "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
				$end   =  '<!-- /JM Twitter Cards -->' . "\n\n";

				if ( is_home() || is_front_page() ) //detect post page or home (could be the same)
				{

					$output  = $begin;
					$output .= '<meta name="twitter:card" content="' . $opts['twitterCardType'] . '"/>' . "\n";
					$output .= '<meta name="twitter:creator" content="@' .static::remove_at($opts['twitterCardCreator']). '"/>' . "\n";
					$output .= '<meta name="twitter:site" content="@' . static::remove_at($opts['twitterCardSite']). '"/>' . "\n";
					$output .= '<meta name="twitter:title" content="' . $opts['twitterCardPostPageTitle'] . '"/>' . "\n";
					$output .= '<meta name="twitter:description" content="' . $opts['twitterCardPostPageDesc'] . '"/>' . "\n";
					$output .= '<meta name="twitter:image:src" content="' . $opts['twitterCardImage'] . '"/>' . "\n";
					
					//Deep linking
					if ($opts['twitterCardDeepLinking'] == 'yes') 
					{
						
						if( $opts['twitterCardiPhoneName'] != '' ) $output .='<meta name="twitter:app:name:iphone" content="' . $opts['twitterCardiPhoneName'] . '">'. "\n";
						if( $opts['twitterCardiPadName'] != '' ) $output .='<meta name="twitter:app:name:ipad" content="' . $opts['twitterCardiPadName'] . '">'. "\n";
						if( $opts['twitterCardGooglePlayName'] != '' ) $output .='<meta name="twitter:app:name:googleplay" content="' . $opts['twitterCardGooglePlayName'] . '">'. "\n";
						if( $opts['twitterCardiPhoneUrl'] != '' ) $output .='<meta name="twitter:app:url:iphone" content="' . $opts['twitterCardiPhoneUrl'] .'">'. "\n";
						if( $opts['twitterCardiPadUrl'] != '' ) $output .='<meta name="twitter:app:url:ipad" content="' . $opts['twitterCardiPhoneUrl'] . '">'. "\n";
						if( $opts['twitterCardGooglePlayUrl'] != '' ) $output .='<meta name="twitter:app:url:googleplay" content="' . $opts['twitterCardGooglePlayUrl'] . '">'. "\n";
						if( $opts['twitterCardiPhoneId'] != '' ) $output .='<meta name="twitter:app:id:iphone" content="' . $opts['twitterCardiPhoneId'] . '">'. "\n";
						if( $opts['twitterCardiPadId'] != '' ) $output .='<meta name="twitter:app:id:ipad" content="' . $opts['twitterCardiPadId'] . '">'. "\n";
						if( $opts['twitterCardGooglePlayId'] != '' ) $output .='<meta name="twitter:app:id:googleplay" content="' . $opts['twitterCardGooglePlayId'] . '">'. "\n";
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
					elseif (  function_exists('get_field') && $opts['twitterCardTitle'] != '' && $opts['twitterCardDesc']!= '' ) // we detect ACF with the function get_field()
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
						$output .= '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $cardType ). '"/>' . "\n";
					}
					else
					{
						$output .= '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $opts['twitterCardType'] ). '"/>' . "\n";
					}

					if ($opts['twitterCardProfile'] == 'yes' && $creator != '' )
					{ // this part has to be optional, this is more for guest bltwitterging but it's no reason to bother everybody.
						$output .= '<meta name="twitter:creator" content="@' . $creator . '"/>' . "\n";
					}
					elseif ($opts['twitterCardProfile'] == 'no' && $username != '' && !is_array($username))
					{ // http://codex.wordpress.org/Function_Reference/get_user_meta#Return_Values
						$output .= '<meta name="twitter:creator" content="@' . static::remove_at($username) . '"/>' . "\n";
					}
					else
					{
						$output .= '<meta name="twitter:creator" content="@' . static::remove_at($opts['twitterCardCreator']) . '"/>' . "\n";
					}

					// these next 4 parameters should not be editable in post admin

					$output .= '<meta name="twitter:site" content="@' . static::remove_at($opts['twitterCardSite']) . '"/>' . "\n";
					$output .= '<meta name="twitter:title" content="' . $cardTitle . '"/>' . "\n"; // filter used by plugin to customize title
					$output .= '<meta name="twitter:description" content="' . static::remove_lb($cardDescription) . '"/>' . "\n";
				
					
					//gallery
					if ($cardType != 'gallery')
					{
						if (get_the_post_thumbnail($post->ID) != '')
						{
							if ($cardImage != '' && $twitterCardCancel != 'yes')
							{ // cardImage is set
								$output .= '<meta name="twitter:image:src" content="' .  apply_filters( 'jm_tc_image_source', $cardImage ). '"/>' . "\n";
							}
							else
							{
								$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , static::thumbnail_sizes());
								$output .= '<meta name="twitter:image:src" content="' . apply_filters( 'jm_tc_image_source', $image_attributes[0] ) . '"/>' . "\n";
							}
						}
						elseif (get_the_post_thumbnail($post->ID) == '' && $cardImage != '' && $twitterCardCancel != 'yes')
						{
							$output .=  '<meta name="twitter:image:src" content="' . apply_filters( 'jm_tc_image_source', $cardImage ) . '"/>' . "\n";
						}
						
						elseif ( 'attachment' == get_post_type() ) 
						{
						
							$output .= '<meta name="twitter:image:src" content="' . apply_filters( 'jm_tc_image_source', wp_get_attachment_url( $post->ID ) ) . '"/>' . "\n";
						
						}
						
						else
						{ //fallback
							$output .= '<meta name="twitter:image:src" content="' . apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ). '"/>' . "\n";
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
										$output .= '<meta name="twitter:image' . $i . '" content="' . apply_filters( 'jm_tc_image_source', $pic ). '"/>' . "\n";
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
								$output .= '<meta name="twitter:image:src" content="' . apply_filters( 'jm_tc_image_source',$image_attributes[0] ). '"/>' . "\n";
							}
							else
							{
								$output .= '<meta name="twitter:image:src" content="' .apply_filters( 'jm_tc_image_source', $opts['twitterCardImage'] ). '"/>' . "\n";
							}
						}
					}
					
					//photo
					if ($opts['twitterCardType'] == 'photo' || $cardType == 'photo'  )
					{
						if ( $cardPhotoWidth != '' && $cardPhotoHeight != '' && $twitterCardCancel != 'yes' )
						{
							$output .= '<meta name="twitter:image:width" content="' . $cardPhotoWidth . '"/>' . "\n";
							$output .= '<meta name="twitter:image:height" content="' . $cardPhotoHeight . '"/>' . "\n";
						}
						elseif ($opts['twitterCardType'] == 'photo' && $twitterCardCancel != 'yes' && $opts['twitterCardMetabox'] != 'yes')
						{
							$output .= '<meta name="twitter:image:width" content="' . $opts['twitterCardImageWidth'] . '"/>' . "\n";
							$output .= '<meta name="twitter:image:height" content="' . $opts['twitterCardImageHeight'] . '"/>' . "\n";
						}
					}
					
					//product
					if ($cardType == 'product' && $twitterCardCancel != 'yes')
					{
						if ( $cardData1 != '' &&  $cardLabel1 != '' && $cardData2 != '' && $cardLabel2 != '' )
						{
							$output .= '<meta name="twitter:data1" content="' . $cardData1 . '"/>' . "\n";
							$output .= '<meta name="twitter:label1" content="' . $cardLabel1 . '"/>' . "\n";
							$output .= '<meta name="twitter:data2" content="' . $cardData2 . '"/>' . "\n";
							$output .= '<meta name="twitter:label2" content="' . $cardLabel2 . '"/>' . "\n";
						}
						else
						{
							$output .=   '<!-- ' .__('Warning : Product Card is not set properly ! There is no product datas !', 'jm-tc').'@(-_-)] -->' . "\n";
						}

						if ( $cardProductWidth != '' && $cardProductHeight != '' && $cardType == 'product')
						{
							$output .= '<meta name="twitter:image:width" content="' . $cardProductWidth . '"/>' . "\n";
							$output .= '<meta name="twitter:image:height" content="' . $cardProductHeight . '"/>' . "\n";
						}
						else
						{
							$output .= '<meta name="twitter:image:width" content="' . $opts['twitterCardImageWidth'] . '"/>' . "\n";
							$output .= '<meta name="twitter:image:height" content="' . $opts['twitterCardImageHeight'] . '"/>' . "\n";
						}
					}
					
					
					if ($cardType == 'player' && $twitterCardCancel != 'yes' )
					{
						if ( $cardPlayer != '' ) {
							$output .= '<meta name="twitter:player" content="' . $cardPlayer . '"/>' . "\n";
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
							$output .= '<meta name="twitter:player:width" content="' . $cardPlayerWidth . '"/>' . "\n";
							$output .= '<meta name="twitter:player:height" content="' . $cardPlayerHeight . '"/>' . "\n";
						}
						else 
						{
							$output .= '<meta name="twitter:player:width" content="435"/>' . "\n";
							$output .= '<meta name="twitter:player:height" content="251"/>' . "\n";		
						}
						
						//Player stream
						if ( $cardPlayerStream != '' ) 
						{
							$output .= '<meta name="twitter:player:stream" content="'.$cardPlayerStream.'">'. "\n";
							$output .= '<meta name="twitter:player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;">'. "\n";
						}
						
						if ( $cardPlayerStream != '' && !preg_match( $regex,$cardPlayerStream ) )
						{
							$output .= '<!-- ' .__('Warning : Player Card is not set properly ! The URL of raw stream Player MUST BE https!', 'jm-tc') . "\n";
						}
						
						
						//Deep linking
						if ($opts['twitterCardDeepLinking'] == 'yes') 
						{
							
							if( $opts['twitteriPhoneName'] != '' ) $output .='<meta name="twitter:app:name:iphone" content="' . $opts['twitteriPhoneName'] . '">'. "\n";
							if( $opts['twitteriPadName'] != '' ) $output .='<meta name="twitter:app:name:ipad" content="' . $opts['twitteriPadName'] . '">'. "\n";
							if( $opts['twitterGooglePlayName'] != '' ) $output .='<meta name="twitter:app:name:googleplay" content="' . $opts['twitterGooglePlayName'] . '">'. "\n";
							if( $opts['twitteriPhoneUrl'] != '' ) $output .='<meta name="twitter:app:url:iphone" content="' . $opts['twitteriPhoneUrl'] .'">'. "\n";
							if( $opts['twitteriPadUrl'] != '' ) $output .='<meta name="twitter:app:url:ipad" content="' . $opts['twitteriPhoneUrl'] . '">'. "\n";
							if( $opts['twitterGooglePlayUrl'] != '' ) $output .='<meta name="twitter:app:url:googleplay" content="' . $opts['twitterGooglePlayUrl'] . '">'. "\n";
							if( $opts['twitteriPhoneId'] != '' ) $output .='<meta name="twitter:app:id:iphone" content="' . $opts['twitteriPhoneId'] . '">'. "\n";
							if( $opts['twitteriPadId'] != '' ) $output .='<meta name="twitter:app:id:ipad" content="' . $opts['twitteriPadId'] . '">'. "\n";
							if( $opts['twitterGooglePlayId'] != '' ) $output .='<meta name="twitter:app:id:googleplay" content="' . $opts['twitterGooglePlayId'] . '">'. "\n";
						}
					}
				
					$output  .= $end;

					echo apply_filters('jmtc_markup', $output); // provide filter for developers.
				
				}	
			}	

	}

}
