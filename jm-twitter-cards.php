<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: https://julien-maury.com/blog
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: https://julien-maury.com/blog
Version: 10.0.1
License: GPL2++

JM Twitter Cards Plugin
Copyright (C) 2015-2018, Julien Maury - contact@julien-maury.com

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
defined( 'ABSPATH' )
or die( 'No direct load !' );

define( 'JM_TC_VERSION', '10.0.1' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) . 'admin/' );
define( 'JM_TC_BASENAME', plugin_basename( __FILE__ ) );
define( 'JM_TC_LANG_DIR', basename( rtrim( dirname( __FILE__ ), '/' ) ) . '/languages' );

$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( file_exists( $autoload ) ) {
	require $autoload;
}

require plugin_dir_path( __FILE__ ) . 'includes/App.php';

/**
 * @since 10.0.0
 */
add_action( 'plugins_loaded', 'jm_tc_run' );
function jm_tc_run() {
	$plugin = new TokenToMe\TwitterCards\Main();
	$plugin->run();
}
