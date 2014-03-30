<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://www.tweetpress.fr
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://www.tweetpress.fr
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


*    Sources: 
* 
* - https://dev.twitter.com/docs/cards
* - https://dev.twitter.com/docs/cards/getting-started#open-graph
* - https://dev.twitter.com/docs/cards/markup-reference
* - https://dev.twitter.com/docs/cards/types/player-card
* - https://dev.twitter.com/docs/cards/app-installs-and-deep-linking [GREAT]
* - http://highlightjs.org/
* - https://dev.twitter.com/discussions/17878
* - https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
* - https://github.com/mustardBees/Custom-Metaboxes-and-Fields-for-WordPress/commit/e76f331139df4f5e8035897fed228efa964da841
* - https://about.twitter.com/fr/press/brand-assets
* - http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/
* - https://trepmal.com/2011/04/03/change-the-virtual-robots-txt-file/
* - https://github.com/trepmal/featured-image-sizes/blob/master/featured-image-sizes.php
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
	
	if ( isset( $_GET['page'] ) && 'jm_tc_about' == $_GET['page'] ) { 
		require( JM_TC_INC_DIR .'admin/pages/about.php' );	
	}
	
}

//Call modules 
if( is_admin() ) {

	require( JM_TC_INC_DIR . 'admin/admin-tc.php' );
	require( JM_TC_INC_DIR . 'admin/notices.php' );
	require( JM_TC_INC_DIR . 'admin/meta-box.php' );

}

// get markup and get it started
require( JM_TC_INC_DIR . 'utilities.php' ); 
require( JM_TC_INC_DIR . 'thumbs.php' );
require( JM_TC_INC_DIR . 'markup.php' ); 


// Add a "Settings" link in the plugins list
function jm_tc_settings_action_links($links, $file)
{
	$settings_link = '<a href="' . admin_url('admin.php?page=jm_tc_options') . '">' . __("Settings") . '</a>';
	array_unshift($links, $settings_link);
	
	return $links;
}


// Init meta box
function jm_tc_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
	require_once JM_TC_METABOX_DIR . 'init.php';

}

// Robots.txt with magic filter
function jm_tc_robots_mod( $output, $public ) {

	$opts = get_option('jm_tc_options');
	
	if( $opts['twitterCardRobotsTxt'] == 'yes' ) {
		$output .= "User-agent: Twitterbot" ."\n";
		$output .= "Disallow: ";
	}
	
	return $output;
}

/******************

INIT

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
	new JM_TC_Thumbs();
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
	delete_option('jm_tc_options');
}