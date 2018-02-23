<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://dev73.tweetpress.fr
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpress.fr
Version: 9.2
License: GPL2++

JM Twitter Cards Plugin
Copyright (C) 2015-2018, Julien Maury - contact@tweetpress.fr

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
define( 'JM_TC_VERSION', defined('SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : '9.2' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );
define( 'JM_TC_LANG_DIR', plugin_basename( dirname( __FILE__ ) ) . '/languages' );

if ( ! defined( 'JM_TC_SLUG_MAIN_OPTION' ) ) {
	define( 'JM_TC_SLUG_MAIN_OPTION', 'jm_tc' );
}

if ( ! defined( 'JM_TC_SLUG_CPT_OPTION' ) ) {
	define( 'JM_TC_SLUG_CPT_OPTION', 'jm_tc_cpt' );
}

/**
 * Autoload this !
 */
if ( ! file_exists( JM_TC_DIR . 'vendor/autoload.php' ) ) {
	return false;
}

require_once( JM_TC_DIR . 'vendor/autoload.php' );

/**
 * CLI commands
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( JM_TC_DIR . 'cli/cli.php' );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jm_tc_settings_action_link' );

/**
 * Re-add Settings link to admin page
 * some users needed it and it does not evil ^^
 *
 * @param $links
 *
 * @return mixed
 */
function jm_tc_settings_action_link( $links ) {
	$links['settings'] = '<a href="' . add_query_arg( array( 'page' => JM_TC_SLUG_MAIN_OPTION ), admin_url( 'admin.php' ) ) . '">' . __( 'Settings' ) . '</a>';

	return $links;
}

register_activation_hook( __FILE__, array( 'TokenToMe\TwitterCards\Admin\Init', 'activate' ) );

add_action( 'plugins_loaded', array( TokenToMe\TwitterCards\Loading::get_instance(), 'plugin_setup' ) );
