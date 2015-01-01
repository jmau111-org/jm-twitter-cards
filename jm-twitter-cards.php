<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://tweetpressfr.github.io
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpressfr.github.io
Version: 5.4.2
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

*    Sources: 
* - https://dev.twitter.com/docs/cards
* - https://dev.twitter.com/docs/cards/getting-started#open-graph
* - https://dev.twitter.com/docs/cards/markup-reference
* - https://dev.twitter.com/docs/cards/types/player-card
* - https://dev.twitter.com/docs/cards/app-installs-and-deep-linking [GREAT]
* - https://dev.twitter.com/discussions/17878
* - https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress [GREAT]
* - https://about.twitter.com/fr/press/brand-assets
* - http://www.jqeasy.com/jquery-character-counter
* - https://trepmal.com/2011/04/03/change-the-virtual-robots-txt-file/ [GREAT]
* - https://github.com/pippinsplugins/Settings-Import-and-Export-Example-Pluginc [GREAT]
* - http://www.wpexplorer.com/wordpress-image-crop-sizes/
*/


//Add some security, no direct load !
defined('ABSPATH')
or die('No direct load !');


//Constantly constant
define('JM_TC_VERSION', '5.4.2');
define('JM_TC_DIR', plugin_dir_path(__FILE__));
define('JM_TC_CLASS_DIR', JM_TC_DIR . 'classes/');
define('JM_TC_ADMIN_CLASS_DIR', JM_TC_DIR . 'classes/admin/');
define('JM_TC_ADMIN_PAGES_DIR', JM_TC_DIR . 'views/pages/');
define('JM_TC_METABOX_DIR', JM_TC_DIR . 'classes/meta-box/');

define('JM_TC_LANG_DIR', dirname(plugin_basename(__FILE__)) . '/languages/');
define('JM_TC_TEXTDOMAIN', 'jm-tc');
define('JM_TC_DOC_TEXTDOMAIN', 'jm-tc-doc');

define('JM_TC_URL', plugin_dir_url(__FILE__));
define('JM_TC_METABOX_URL', JM_TC_URL . 'classes/meta-box/');
define('JM_TC_IMG_URL', JM_TC_URL . 'assets/img/');
define('JM_TC_CSS_URL', JM_TC_URL . 'assets/css/');
define('JM_TC_JS_URL', JM_TC_URL . 'assets/js/');


//Call modules 
require(JM_TC_CLASS_DIR . 'init.class.php');
require(JM_TC_DIR       . 'functions/functions.inc.php');
require(JM_TC_CLASS_DIR . 'utilities.class.php');
require(JM_TC_CLASS_DIR . 'particular.class.php');
require(JM_TC_CLASS_DIR . 'thumbs.class.php');
require(JM_TC_CLASS_DIR . 'disable.class.php');
require(JM_TC_CLASS_DIR . 'options.class.php');
require(JM_TC_CLASS_DIR . 'markup.class.php');

if (is_admin()) {

    require(JM_TC_ADMIN_CLASS_DIR . 'author.class.php');
    require(JM_TC_ADMIN_CLASS_DIR . 'tabs.class.php');
    require(JM_TC_ADMIN_CLASS_DIR . 'admin-tc.class.php');
    require(JM_TC_ADMIN_CLASS_DIR . 'preview.class.php');
    require(JM_TC_ADMIN_CLASS_DIR . 'meta-box.class.php');
    require(JM_TC_ADMIN_CLASS_DIR . 'import-export.class.php');

}

/******************
 * CLASS INIT
 ******************/

global $jm_twitter_cards;

//check if Twitter cards is activated in Yoast and deactivate it
$jm_twitter_cards['disable'] = new JM_TC_Disable;

//handle particular cases	
$jm_twitter_cards['particular'] = new JM_TC_Particular;

//admin classes
if (is_admin()) {

    $jm_twitter_cards['init'] = new JM_TC_init;
    $jm_twitter_cards['utilities'] = new JM_TC_Utilities;
    $jm_twitter_cards['admin-tabs'] = new JM_TC_Tabs;
    $jm_twitter_cards['admin-base'] = new JM_TC_Admin;
    $jm_twitter_cards['admin-import-export'] = new JM_TC_Import_Export;
    $jm_twitter_cards['admin-preview'] = new JM_TC_Preview;
    $jm_twitter_cards['admin-metabox'] = new JM_TC_Metabox;
    $jm_twitter_cards['admin-about'] = new JM_TC_Author;

}

$jm_twitter_cards['process-thumbs'] = new JM_TC_Thumbs;
$jm_twitter_cards['populate-markup'] = new JM_TC_Markup;


/*
* On activation
*/
register_activation_hook(__FILE__, array('JM_TC_Init', 'activate'));


/**
 * Everything that should trigger early
 */
add_action('plugins_loaded', 'jm_tc_plugins_loaded');
function jm_tc_plugins_loaded(){

    // init metabox
    add_action('init', array('JM_TC_Init', 'initialize'));

    //langs
    load_plugin_textdomain(JM_TC_TEXTDOMAIN, false, JM_TC_LANG_DIR);

    if (is_admin()) {

        load_plugin_textdomain(JM_TC_DOC_TEXTDOMAIN, false, JM_TC_LANG_DIR);

    }

    //markup
    global $jm_twitter_cards;
    $init_markup = $jm_twitter_cards['populate-markup'];

    add_action('wp_head', array($init_markup, 'add_markup'), 2);
}
