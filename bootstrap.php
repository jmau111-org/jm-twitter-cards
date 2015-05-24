<?php
// Add some security, no direct load !
defined( 'ABSPATH' )
or die( 'No direct load !' );

register_activation_hook( __FILE__, array( 'TokenToMe\twitter_cards\Init', 'activate' ) );

/**
 * Everything that should trigger early
 */
add_action( 'plugins_loaded', 'jm_tc_plugins_loaded' );
function jm_tc_plugins_loaded() {

	// init metabox
	add_action( 'init', array( 'TokenToMe\twitter_cards\Thumbs', 'add_image_sizes' ) );

	/******************
	 * CLASS INIT
	 ******************/

	// check if Twitter cards is activated in Yoast and deactivate it
	$GLOBALS['jm_twitter_cards']['disable'] = new TokenToMe\twitter_cards\Disable;

	// handle particular cases
	$GLOBALS['jm_twitter_cards']['particular'] = new TokenToMe\twitter_cards\Particular;

	//admin classes
	if ( is_admin() ) {

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

	//markup
	add_action( 'wp_head', array( $GLOBALS['jm_twitter_cards']['populate-markup'], 'add_markup' ), 2 );
}

/**
 * Call the metabox framework
 */
add_action( 'init', 'jm_tc_init' );
function jm_tc_init(){

	if ( ! class_exists( 'cmb_Meta_Box' ) ) {
		require_once JM_TC_DIR . 'library/metaboxes/init.php';
	}
}