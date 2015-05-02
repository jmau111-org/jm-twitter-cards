<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://tweetpressfr.github.io
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpressfr.github.io
Version: 5.5
License: GPL2++

JM Twitter Cards Plugin
Copyright (C) 2013-2015, Julien Maury - contact@tweetpress.fr

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
*/

// Add some security, no direct load !
defined( 'ABSPATH' )
or die( 'No direct load !' );

// Constantly constant
define( 'JM_TC_VERSION', '5.5' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JM_TC_CLASS_DIR', JM_TC_DIR . 'classes/' );
define( 'JM_TC_ADMIN_CLASS_DIR', JM_TC_DIR . 'classes/admin/' );
define( 'JM_TC_ADMIN_PAGES_DIR', JM_TC_DIR . 'views/pages/' );
define( 'JM_TC_METABOX_DIR', JM_TC_DIR . 'library/metaboxes/' );

define( 'JM_TC_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
define( 'JM_TC_TEXTDOMAIN', 'jm-tc' );
define( 'JM_TC_DOC_TEXTDOMAIN', 'jm-tc-doc' );

define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );
define( 'JM_TC_METABOX_URL', JM_TC_URL . 'library/metaboxes/' );
define( 'JM_TC_IMG_URL', JM_TC_URL . 'assets/img/' );
define( 'JM_TC_CSS_URL', JM_TC_URL . 'assets/css/' );
define( 'JM_TC_JS_URL', JM_TC_URL . 'assets/js/' );

// Function for easy load files
function _jm_tc_load_files( $dir, $files, $suffix = '' ) {
	foreach ( $files as $file ) {
		if ( is_file( $dir . $file . '.' . $suffix . '.php' ) ) {
			require_once( $dir . $file . '.' . $suffix . '.php' );
		}
	}
}

// Call modules
_jm_tc_load_files( JM_TC_CLASS_DIR, array( 'init', 'utilities', 'particular', 'thumbs', 'disable', 'options', 'markup' ), 'class' );
_jm_tc_load_files( JM_TC_DIR . 'functions/', array( 'functions' ), 'inc' );

if ( is_admin() ) {
	_jm_tc_load_files( JM_TC_ADMIN_CLASS_DIR, array( 'author', 'tabs', 'admin-tc', 'preview', 'metabox', 'import-export' ), 'class' );
}

register_activation_hook( __FILE__, array( 'TokenToMe\twitter_cards\Init', 'activate' ) );

/**
 * Everything that should trigger early
 */
add_action( 'plugins_loaded', 'jm_tc_plugins_loaded' );
function jm_tc_plugins_loaded() {

	// init metabox
	add_action( 'init', array( 'TokenToMe\twitter_cards\Init', 'initialize' ) );

	/******************
	 * CLASS INIT
	 ******************/

	// check if Twitter cards is activated in Yoast and deactivate it
	$GLOBALS['jm_twitter_cards']['disable'] = new TokenToMe\twitter_cards\Disable;

	// handle particular cases
	$GLOBALS['jm_twitter_cards']['particular'] = new TokenToMe\twitter_cards\Particular;

	//admin classes
	if ( is_admin() ) {

		$GLOBALS['jm_twitter_cards']['init']                = new TokenToMe\twitter_cards\init;
		$GLOBALS['jm_twitter_cards']['utilities']           = new TokenToMe\twitter_cards\Utilities;
		$GLOBALS['jm_twitter_cards']['admin-tabs']          = new TokenToMe\twitter_cards\Tabs;
		$GLOBALS['jm_twitter_cards']['admin-base']          = new TokenToMe\twitter_cards\Admin;
		$GLOBALS['jm_twitter_cards']['admin-import-export'] = new TokenToMe\twitter_cards\Import_Export;
		$GLOBALS['jm_twitter_cards']['admin-preview']       = new TokenToMe\twitter_cards\Preview;
		$GLOBALS['jm_twitter_cards']['admin-metabox']       = new TokenToMe\twitter_cards\Metabox;
		$GLOBALS['jm_twitter_cards']['admin-about']         = new TokenToMe\twitter_cards\Author;

	}

	$GLOBALS['jm_twitter_cards']['process-thumbs']  = new TokenToMe\twitter_cards\Thumbs;
	$GLOBALS['jm_twitter_cards']['populate-markup'] = new TokenToMe\twitter_cards\Markup;

	//langs
	load_plugin_textdomain( JM_TC_TEXTDOMAIN, false, JM_TC_LANG_DIR );

	if ( is_admin() ) {

		load_plugin_textdomain( JM_TC_DOC_TEXTDOMAIN, false, JM_TC_LANG_DIR );

	}

	//markup
	add_action( 'wp_head', array( $GLOBALS['jm_twitter_cards']['populate-markup'], 'add_markup' ), 2 );
}
