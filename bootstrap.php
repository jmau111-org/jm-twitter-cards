<?php

namespace TokenToMe\TwitterCards;

// Add some security, no direct load !
defined('ABSPATH')
or die('No direct load !');

register_activation_hook(__FILE__, array('Init', 'TokenToMe\TwitterCards\activate'));

/**
 * Everything that should trigger early
 */
add_action('plugins_loaded', 'TokenToMe\TwitterCards\_plugins_loaded');
function _plugins_loaded()
{

    // init metabox
    add_action('init', array('TokenToMe\TwitterCards\Thumbs', 'add_image_sizes'));

    // check if Twitter cards is activated in Yoast and deactivate it
    $GLOBALS['jm_twitter_cards']['disable'] = new Disable;

    // handle particular cases
    $GLOBALS['jm_twitter_cards']['particular'] = new Particular;


    $GLOBALS['jm_twitter_cards']['utilities'] = new Utilities;

    //admin classes
    if (is_admin()) {

        $GLOBALS['jm_twitter_cards']['admin-base'] = new Admin\Main;
        $GLOBALS['jm_twitter_cards']['admin-import-export'] = new Admin\ImportExport;
        $GLOBALS['jm_twitter_cards']['admin-preview'] = new Admin\Preview;
        $GLOBALS['jm_twitter_cards']['admin-metabox'] = new Admin\Metabox;

    }

    $GLOBALS['jm_twitter_cards']['populate-markup'] = new Markup;

    //langs
    load_plugin_textdomain(JM_TC_TEXTDOMAIN, false, JM_TC_LANG_DIR);

    //markup
    add_action('wp_head', array($GLOBALS['jm_twitter_cards']['populate-markup'], 'add_markup'), 2);
}

/**
 * Call the metabox framework
 */
add_action('init', 'TokenToMe\TwitterCards\_init');
function _init()
{

    if (!class_exists('cmb_Meta_Box')) {
        require_once JM_TC_DIR . 'library/metaboxes/init.php';
    }
}