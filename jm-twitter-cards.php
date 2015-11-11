<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://tweetpressfr.github.io
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpressfr.github.io
Version: 7.0
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
define( 'JM_TC_VERSION', '7.0' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoload this !
 */
require_once ( JM_TC_DIR . 'vendor/autoload.php' );
require_once ( JM_TC_DIR . 'vendor/rilwis/meta-box/meta-box.php' );

register_activation_hook( __FILE__, array( 'TokenToMe\TwitterCards\Admin\Init', 'activate' ) );

add_action( 'plugins_loaded', '_jm_tc_plugins_loaded' );
function _jm_tc_plugins_loaded() {

	if ( is_admin() ) {
		new \TokenToMe\TwitterCards\Admin\Main();
		new \TokenToMe\TwitterCards\Admin\ImportExport();
		new \TokenToMe\TwitterCards\Admin\Options();
		new \TokenToMe\TwitterCards\Admin\Meta_Box();
	} else {
		new \TokenToMe\TwitterCards\Main();
		new \TokenToMe\TwitterCards\Thumbs();
		new \TokenToMe\TwitterCards\Markup();
	}

	//langs
	load_plugin_textdomain( 'jm-tc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}