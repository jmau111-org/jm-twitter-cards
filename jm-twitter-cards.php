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

define( 'JM_TC_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
define( 'JM_TC_TEXTDOMAIN', 'jm-tc' );

define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );
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

if ( version_compare( '5.3', phpversion(), '>' ) ) {
	add_action( 'admin_notices', 'jm_tc_check_php_version_notif', 0 );
	/**
	 * Check if current PHP version is newer than 5.4
	 * @author Julien Maury
	 */
	function jm_tc_check_php_version_notif(){

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		printf(__('<div class="error"><p>%1$s requires PHP 5.3 at least</p></div>'), 'JM Twitter cards' );

	}

} else {

	// Call modules
	_jm_tc_load_files( JM_TC_DIR . 'classes/', array(
		'utilities',
		'particular',
		'thumbs',
		'disable',
		'options',
		'markup',
	), 'class' );

	if ( is_admin() ) {
		_jm_tc_load_files( JM_TC_DIR . 'classes/admin/', array(
			'author',
			'tabs',
			'admin-tc',
			'preview',
			'metabox',
			'import-export',
		), 'class' );
	}
	_jm_tc_load_files( JM_TC_DIR . 'functions/', array( 'functions' ), 'inc' );
	require JM_TC_DIR . 'bootstrap.php';
}