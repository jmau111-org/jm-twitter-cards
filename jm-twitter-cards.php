<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://tweetpressfr.github.io
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpressfr.github.io
Version: 6.0
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
define( 'JM_TC_VERSION', '6.0' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );

define( 'JM_TC_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
define( 'JM_TC_TEXTDOMAIN', 'jm-tc' );

define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );
define( 'JM_TC_CSS_URL', JM_TC_URL . 'css/' );
define( 'JM_TC_JS_URL', JM_TC_URL . 'js/' );

if ( version_compare( '5.3', phpversion(), '>' ) ) {
	add_action( 'admin_notices', 'jm_tc_check_php_version_notif', 0 );
	/**
	 * Check if current PHP version is newer than 5.4
	 * @author Julien Maury
	 */
	function jm_tc_check_php_version_notif() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		printf( __( '<div class="error"><p>%1$s requires PHP 5.3 at least</p></div>' ), 'JM Twitter cards' );
	}

} else {

	register_activation_hook( __FILE__, array( 'TokenToMe\TwitterCards\Admin\Init', 'activate' ) );

	require( JM_TC_DIR . 'functions/functions.inc.php' );
	require( JM_TC_DIR . 'autoload.php' );
	require( JM_TC_DIR . 'bootstrap.php' );
}