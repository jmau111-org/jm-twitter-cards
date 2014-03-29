<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://www.tweetpress.fr
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://www.tweetpress.fr
<<<<<<< HEAD
Version: 5.0
License: GPL2++

JM Twitter Cards Plugin
Copyright (C) 2013-2014, Julien Maury - contact@tweetpress.fr

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.


*    Sources:  - https://dev.twitter.com/docs/cards
*              - https://dev.twitter.com/docs/cards/getting-started#open-graph
*              - https://dev.twitter.com/docs/cards/markup-reference
*			   - https://dev.twitter.com/docs/cards/types/player-card
*			   - https://dev.twitter.com/docs/cards/app-installs-and-deep-linking [GREAT]
*			   - http://highlightjs.org/
*			   - https://dev.twitter.com/discussions/17878
*			   - https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
*			   - https://github.com/mustardBees/Custom-Metaboxes-and-Fields-for-WordPress/commit/e76f331139df4f5e8035897fed228efa964da841
*			   - https://about.twitter.com/fr/press/brand-assets
*			   - http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/
*			   - https://trepmal.com/2011/04/03/change-the-virtual-robots-txt-file/
=======
Version: 4.4.1
License: GPL2++
*/
/*
*    Sources:  	- https://dev.twitter.com/docs/cards
*              	- http://codex.wordpress.org/Function_Reference/wp_enqueue_style
*              	- https://github.com/rilwis/meta-box [GREAT]
*              	- http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
*              	- http://codex.wordpress.org/Function_Reference/get_user_meta
*              	- http://codex.wordpress.org/Function_Reference/get_posts
*              	- https://codex.wordpress.org/Function_Reference/has_shortcode
*              	- http://codex.wordpress.org/AJAX_in_Plugins
*              	- http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
*              	- http://byronyasgur.wordpress.com/2011/06/27/frontend-forward-facing-ajax-in-wordpress/
*              	- https://dev.twitter.com/docs/cards/getting-started#open-graph
* 		- https://dev.twitter.com/docs/cards/markup-reference
*		- https://dev.twitter.com/docs/cards/types/player-card
*		- https://dev.twitter.com/docs/cards/app-installs-and-deep-linking [GREAT]
*		- http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/
*		- http://highlightjs.org/
*		- https://dev.twitter.com/discussions/17878
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2
*/

//Add some security, no direct load !
defined('ABSPATH') 
	or die('What we\'re dealing with here is a total lack of respect for the law !');

//Constantly constant
define( 'JM_TC_VERSION', 			'5.0' );
define( 'JM_TC_DIR', 				plugin_dir_path( __FILE__ )  );
define( 'JM_TC_INC_DIR', 			trailingslashit(JM_TC_DIR . 'inc') );
define( 'JM_TC_METABOX_DIR', 		trailingslashit(JM_TC_INC_DIR . 'admin/meta-box') );
define( 'JM_TC_LANG_DIR', 			dirname(plugin_basename(__FILE__)) . '/languages/' );
define( 'JM_TC_URL', 				trailingslashit(plugin_dir_url( __FILE__ ).'inc/admin') );
define( 'JM_TC_METABOX_URL', 		trailingslashit(JM_TC_URL.'admin/meta-box') );
define( 'JM_TC_IMG_URL', 			trailingslashit(JM_TC_URL.'img') );
define( 'JM_TC_CSS_URL', 			trailingslashit(JM_TC_URL.'css') );
define( 'JM_TC_JS_URL', 			trailingslashit(JM_TC_URL.'js') );


//Call pages
function jm_tc_subpages(){
	if ( isset( $_GET['page'] ) && 'jm_tc_doc' == $_GET['page'] ) { 
		require( JM_TC_INC_DIR .'admin/pages/documentation.php' );	
	 }
	 
	 if ( isset( $_GET['page'] ) && 'jm_tc_robots_txt' == $_GET['page'] ) { 
		require( JM_TC_INC_DIR .'admin/pages/robots.php' );	
	 }
	 
}

//Call modules 
if( is_admin() ) {

	require( JM_TC_INC_DIR . 'admin/admin-tc.php' );
	require( JM_TC_INC_DIR . 'admin/notices.php' );
	require( JM_TC_INC_DIR . 'admin/meta-box.php' );

<<<<<<< HEAD
=======
	add_filter('user_profile_update_errors', 'jm_tc_check_at', 10, 3); // wp-admin/includes/users.php, thanks Greglone for this great hint
	function jm_tc_check_at($errors, $update, $user)
	{
		if ($update)
		{

			// let's save it but in case there's a @ just remove it before saving

			update_user_meta($user->ID, 'jm_tc_twitter', jm_tc_remove_at($_POST['jm_tc_twitter']));
		}
	}
}

// grab excerpt

if (!function_exists('get_excerpt_by_id'))
{
	function get_excerpt_by_id($post_id)
	{
		$the_post = get_post($post_id);
		$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

		// SET LENGTH

		$opts = jm_tc_get_options();
		$excerpt_length = $opts['twitterExcerptLength'];
		
		$the_excerpt = strip_shortcodes($the_excerpt); // prevent shortcodes from appearing in description
		$the_excerpt = wp_trim_words( $the_excerpt, $excerpt_length, '');// it's better to use wp functions 
		
		return esc_attr($the_excerpt); // to prevent meta from being broken by ""
	}
}

// function to add markup in head section of post types
if (!function_exists('_jm_tc_markup_home'))
{
	function _jm_tc_markup_home()
    {
	/* get options */
	$opts = jm_tc_get_options();

		$output  = '<meta name="twitter:card" content="' . $opts['twitterCardType'] . '"/>' . "\n";
		$output .= '<meta name="twitter:creator" content="@' . $opts['twitterCreator'] . '"/>' . "\n";
		$output .= '<meta name="twitter:site" content="@' . $opts['twitterSite'] . '"/>' . "\n";
		$output .= '<meta name="twitter:title" content="' . $opts['twitterPostPageTitle'] . '"/>' . "\n";
		$output .= '<meta name="twitter:description" content="' . $opts['twitterPostPageDesc'] . '"/>' . "\n";
		$output .= '<meta name="twitter:image" content="' . $opts['twitterImage'] . '"/>' . "\n";
		
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
		
		return apply_filters('jmtc_markup_home', $output); // provide filter for developers.
			
	}
}

if (!function_exists('_jm_tc_markup'))
{
	function _jm_tc_markup()
	{
		global $post;
		/* get options */
		$opts = jm_tc_get_options();

			// get current post meta data

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

			$cardTitleKey = $opts['twitterCardTitle'];
			$cardDescKey = $opts['twitterCardDesc'];
			$cardUsernameKey = $opts['twitterUsernameKey'];
			/* custom fields */
			$tctitle = get_post_meta($post->ID, $cardTitleKey, true);
			$tcdesc = get_post_meta($post->ID, $cardDescKey, true);
			$username = get_user_meta(get_current_user_id() , $cardUsernameKey, true);

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
					$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
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
					$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
				}
			}
			elseif ($tctitle && $tcdesc && $cardTitleKey != '' && $cardDescKey != '')
			{

				// avoid array to string notice on title and desc

				$cardTitle = $tctitle;
				$cardDescription = $tcdesc;
			}
			else
			{ //default (I'll probably make a switch next time)
				$cardTitle = the_title_attribute(array(
					'echo' => false
				));
				$cardDescription = apply_filters('jm_tc_get_excerpt', get_excerpt_by_id($post->ID) );
			}

			if (($opts['twitterCardMetabox'] == 'yes') && $cardType != '' && $twitterCardCancel != 'yes')
			{
				$output = '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $cardType ). '"/>' . "\n";
			}
			else
			{
				$output = '<meta name="twitter:card" content="' . apply_filters('jm_tc_card_type', $opts['twitterCardType'] ). '"/>' . "\n";
			}

			if ($opts['twitterProfile'] == 'yes' && $creator != '' )
			{ // this part has to be optional, this is more for guest bltwitterging but it's no reason to bother everybody.
				$output .= '<meta name="twitter:creator" content="@' . $creator . '"/>' . "\n";
			}
			elseif ($opts['twitterProfile'] == 'no' && $username != '' && !is_array($username))
			{ // http://codex.wordpress.org/Function_Reference/get_user_meta#Return_Values
				$output .= '<meta name="twitter:creator" content="@' . $username . '"/>' . "\n";
			}
			else
			{
				$output .= '<meta name="twitter:creator" content="@' . $opts['twitterCreator'] . '"/>' . "\n";
			}

			// these next 4 parameters should not be editable in post admin

			$output .= '<meta name="twitter:site" content="@' . $opts['twitterSite'] . '"/>' . "\n";
			$output .= '<meta name="twitter:title" content="' . $cardTitle . '"/>' . "\n"; // filter used by plugin to customize title
			$output .= '<meta name="twitter:description" content="' . jm_tc_remove_lb($cardDescription) . '"/>' . "\n";
		
			
			//gallery
			if ($cardType != 'gallery')
			{
				if (get_the_post_thumbnail($post->ID) != '')
				{
					if ($cardImage != '' && $twitterCardCancel != 'yes')
					{ // cardImage is set
						$output .= '<meta name="twitter:image" content="' .  apply_filters( 'jm_tc_image_source', $cardImage ). '"/>' . "\n";
					}
					else
					{
						$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , jm_tc_thumbnail_sizes());
						$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $image_attributes[0] ) . '"/>' . "\n";
					}
				}
				elseif (get_the_post_thumbnail($post->ID) == '' && $cardImage != '' && $twitterCardCancel != 'yes')
				{
					$output .=  '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $cardImage ) . '"/>' . "\n";
				}
				
				elseif ( 'attachment' == get_post_type() ) 
				{
				
					$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', wp_get_attachment_url( $post->ID ) ) . '"/>' . "\n";
				
				}
				
				else
				{ //fallback
					$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source', $opts['twitterImage'] ). '"/>' . "\n";
				}
			}
			else
			{ // markup will be different
				if ($twitterCardCancel != 'yes')
				{
					if (jm_tc_has_shortcode($post->post_content, 'gallery'))
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
						$image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , jm_tc_thumbnail_sizes());
						$output .= '<meta name="twitter:image" content="' . apply_filters( 'jm_tc_image_source',$image_attributes[0] ). '"/>' . "\n";
					}
					else
					{
						$output .= '<meta name="twitter:image" content="' .apply_filters( 'jm_tc_image_source', $opts['twitterImage'] ). '"/>' . "\n";
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
					$output .= '<meta name="twitter:image:width" content="' . $opts['twitterImageWidth'] . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $opts['twitterImageHeight'] . '"/>' . "\n";
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
					$output .= '<meta name="twitter:image:width" content="' . $opts['twitterImageWidth'] . '"/>' . "\n";
					$output .= '<meta name="twitter:image:height" content="' . $opts['twitterImageHeight'] . '"/>' . "\n";
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
				
				if ( $cardPlayerStream != '' && !preg_match( $regex,$cardPlayerStream) )
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
	

		return apply_filters('jmtc_markup', $output); // provide filter for developers.
	}
}	

//Add markup according to which page is displayed
//add_action('wp_head', '_jm_tc_add_markup', PHP_INT_MAX); // it's actually better to load twitter card meta at the very end (SEO desc is more important)

add_action('wp_head', '_jm_tc_add_markup',2); //the priority level used to be a little bit ^^ lower before according this thread https://dev.twitter.com/discussions/17878
function _jm_tc_add_markup() {

        $begin =  "\n" . '<!-- JM Twitter Cards by Julien Maury ' . jm_tc_plugin_get_version() . ' -->' . "\n";
		$end   =  '<!-- /JM Twitter Cards -->' . "\n\n";


		if ( is_home() || is_front_page() ) //detect post page or home (could be the same)
		{
		  echo $begin;
		  echo _jm_tc_markup_home();
		  echo $end;
		}
		
		if( is_singular() && !is_front_page() && !is_home() && !is_404() && !is_tag() ) // avoid pages that do not need cards
		{
		  echo $begin;
		  echo _jm_tc_markup();
		  echo $end;
		}
		
}


/*
* ADMIN OPTION PAGE
*/

// Language support
add_action('plugins_loaded', 'jm_tc_lang_init');
function jm_tc_lang_init()
{
	load_plugin_textdomain('jm-tc', false, dirname(plugin_basename(__FILE__)) . '/languages/');
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2
}

// get markup and get it started
require( JM_TC_INC_DIR . 'utilities.php' ); 
require( JM_TC_INC_DIR . 'markup.php' ); 


// Add a "Settings" link in the plugins list
<<<<<<< HEAD
=======
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'jm_tc_settings_action_links', 10, 2);
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2
function jm_tc_settings_action_links($links, $file)
{
	$settings_link = '<a href="' . admin_url('admin.php?page=jm_tc_options') . '">' . __("Settings") . '</a>';
	array_unshift($links, $settings_link);
	
	return $links;
}

<<<<<<< HEAD

// Init meta box
function jm_tc_initialize_cmb_meta_boxes() {
=======
// The add_action to add onto the WordPress menu.
add_action('admin_menu', 'jm_tc_add_options');
function jm_tc_add_options()
{
	$tcpage    = add_menu_page('JM Twitter Cards Options', __('Twitter Cards','jm-tc'), 'manage_options', 'jm_tc_options', 'jm_tc_options_page', plugins_url('admin/img/bird_blue_16.png', __FILE__) , 99);
	//add submenu
	$tcdocpage = add_submenu_page( 'jm_tc_options', __( 'Documentation', 'jm-tc' ), __( 'Documentation', 'jm-tc' ) , 'manage_options', 'jm_tc_doc', 'jm_tc_docu_page' );
	register_setting('jm-tc', 'jm_tc', 'jm_tc_sanitize');
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once JM_TC_METABOX_DIR . 'init.php';

}

// Robots.txt with magic filter
function jm_tc_robots_mod( $output, $public ) {

<<<<<<< HEAD
	$opts = get_option('jm_tc_options');
	
	if( $opts['twitterCardRobotsTxt'] == 'yes' ) {
		$output .= "User-agent: Twitterbot" ."\n";
		$output .= "Disallow: ";
=======
function jm_tc_admin_script()
{
	wp_enqueue_style('jm-tc-admin-style', plugins_url('admin/css/jm-tc-admin.css', __FILE__));
	wp_enqueue_script('jm-tc-admin-script', plugins_url('admin/js/jm-tc-admin.js', __FILE__) , array(
		'jquery'
	) , '1.0', true);
	wp_localize_script('jm-tc-admin-script', 'jmTcObject', array(
		'ajaxurl' => esc_url(admin_url('/admin-ajax.php')) ,
		'_tc_ajax_saving_nonce' => wp_create_nonce('tc-ajax-saving-nonce')
	));
}
function jm_tc_doc_scripts()
{
	wp_enqueue_style('jm-tc-doc-style', plugins_url('admin/css/jm-tc-documentation.css', __FILE__));
}

// Ajax saving options
add_action('wp_ajax_jm-tc-ajax-saving', 'jm_tc_ajax_saving_process');
function jm_tc_ajax_saving_process()
{

	// security for our AJAX request

	if (!isset($_POST['_tc_ajax_saving_nonce']) || !wp_verify_nonce($_POST['_tc_ajax_saving_nonce'], 'tc-ajax-saving-nonce')) die('No no, no no no no, there\'s a limit !');
	if (current_user_can('manage_options'))
	{
		$response = __('Options have been saved.', 'jm-tc');
		echo $response;
	}
	else
	{
		echo __('No way :/', 'jm-tc');
	}

	// IMPORTANT: don't forget to "exit"

	exit;
}

// Add styles to post edit the WordPress Way >> http://codex.wordpress.org/Function_Reference/wp_enqueue_style#Load_stylesheet_only_on_a_plugin.27s_options_page
add_action('admin_enqueue_scripts', 'jm_tc_metabox_scripts'); // the right hook to add style in admin area
function jm_tc_metabox_scripts($hook_suffix)
{
	$opts = jm_tc_get_options();
	if (('post.php' == $hook_suffix || 'post-new.php' == $hook_suffix) && $opts['twitterCardMetabox'] == 'yes')
	{
		wp_enqueue_style('jm-tc-style-metabox', plugins_url('admin/css/jm-tc-metabox.css', __FILE__));
		wp_enqueue_script('jm-tc-script-metabox', plugins_url('admin/js/jm-tc-metabox.js', __FILE__) , array(
			'jquery'
		) ); 
		
		global $post_type;
		if( get_post_type() == $post_type) {
			if(function_exists('wp_enqueue_media')) {
				wp_enqueue_media();
			}
			else {
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
			}
		}
	}
}

// Add dismissible notice

add_action('admin_notices', 'jm_tc_admin_notice');
if (!function_exists('jm_tc_admin_notice'))
{
	function jm_tc_admin_notice()
	{
		global $current_user;
		$user_id = $current_user->ID;

		// WP SEO Card Option

		if (!get_user_meta($user_id, 'jm_tc_ignore_notice') && current_user_can('install_plugins') && class_exists('WPSEO_Frontend'))
		{
			echo '<div class="error"><p>';
			printf(__('WordPress SEO by Yoast is activated, please uncheck Twitter Card option in this plugin if it is enabled to avoid adding markup twice | <a href="%1$s">Hide Notice</a>') , '?jm_tc_ignore_this=0', 'jm-tc');
			echo "</p></div>";
		}

		// Jetpack Open Graph - Jet pack has been updated yeah ! Thanks guys.

>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2
	}
	
	return $output;
}

<<<<<<< HEAD
/******************

 INIT
=======
add_action('admin_init', 'jm_tc_ignore_this');
if (!function_exists('jm_tc_ignore_this'))
{
	function jm_tc_ignore_this()
	{
		global $current_user;
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add that to their user meta */
		if (isset($_GET['jm_tc_ignore_this']) && '0' == $_GET['jm_tc_ignore_this'])
		{
			add_user_meta($user_id, 'jm_tc_ignore_notice', 'true', true);
		}
	}
}
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2

******************/
add_action('plugins_loaded', 'jm_tc_init');
function jm_tc_init()
{
	//lang
	load_plugin_textdomain('jm-tc', false, JM_TC_LANG_DIR);
	
	//settings link
	add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'jm_tc_settings_action_links', 10, 2);
	
	//meta box
	add_action( 'init', 'jm_tc_initialize_cmb_meta_boxes', PHP_INT_MAX );
	
	//robots.txt
	add_filter( 'robots_txt', 'jm_tc_robots_mod', PHP_INT_MAX, 2 );
	
	
	new JM_TC_Utilities();
	new JM_TC_Markup();

	if( is_admin() ) {
		
		$admin  	= new JM_TC_Admin(); 
		$metabox	= new JM_TC_Metabox();
		
		
		new JM_TC_Notices();
		
		
		$admin->hooks();
		$metabox->hooks();

	}
	
}

//Plugin install : update options
register_activation_hook(__FILE__, 'jm_tc_on_activation');
function jm_tc_on_activation()
{
	$opts = get_option('jm_tc_options');	
	if (!is_array($opts)) update_option('jm_tc_options', jm_tc_get_default_options());
}


// Return default options
function jm_tc_get_default_options()
{
	return array(
		'twitterCardType' 			=> 'summary',
		'twitterCardCreator' 		=> 'TweetPressFr',
		'twitterCardSite' 			=> 'TweetPressFr',
		'twitterCardExcerptLength' 	=> 35,
		'twitterCardImage'			=> 'https://g.twimg.com/Twitter_logo_blue.png',
		'twitterCardImageWidth' 	=> 280,
		'twitterCardImageHeight' 	=> 150,
		'twitterCardMetabox' 		=> 'no',
		'twitterCardProfile' 		=> 'no',
		'twitterCardPostPageTitle' 	=> get_bloginfo('name') , // filter used by plugin to customize title
		'twitterCardPostPageDesc' 	=> __('Welcome to', 'jm-tc') . ' ' . get_bloginfo('name') . ' - ' . __('see blog posts', 'jm-tc') ,
		'twitterCardSEOTitle' 		=> 'yes',
		'twitterCardSEODesc' 		=> 'yes',
		'twitterCardImageSize' 		=> 'small',
		'twitterCardTitle' 			=> '',
		'twitterCardDesc' 			=> '',
		'twitterCardCrop' 			=> 'yes',
		'twitterCardUsernameKey' 	=> 'jm_tc_twitter',
		'twitterCardDeepLinking' 	=> 'no',
		'twitterCardiPhoneName' 	=> '',
		'twitterCardiPadName' 		=> '',
		'twitterCardGooglePlayName' => '',
		'twitterCardiPhoneUrl' 		=> '',
		'twitterCardiPadUrl'		=> '',
		'twitterCardGooglePlayUrl' 	=> '',
		'twitterCardiPhoneId' 		=> '',
		'twitterCardiPadId'			=> '',
		'twitterCardGooglePlayId'   => '',
		'twitterCardRobotsTxt'		=> 'yes'
	);
}




// Plugin uninstall: delete option
register_uninstall_hook(__FILE__, 'jm_tc_uninstall');
function jm_tc_uninstall()
{
<<<<<<< HEAD
	delete_option('jm_tc_options');
}
=======
	$options = get_option('jm_tc');
	return array_merge(jm_tc_get_default_options() , jm_tc_sanitize_options($options));
}
>>>>>>> 3e52b22f026bf49cbc32f71ae769edc11c7c2bc2
