<?php
/*
Plugin Name: JM Twitter Cards
Plugin URI: http://dev73.tweetpress.fr
Description: Meant to help users to implement and customize Twitter Cards easily
Author: Julien Maury
Author URI: http://tweetpress.fr
Version: 7.4.0
License: GPL2++

JM Twitter Cards Plugin
Copyright (C) 2016, Julien Maury - contact@tweetpress.fr

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
define( 'JM_TC_VERSION', '7.4.0' );
define( 'JM_TC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JM_TC_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoload this !
 */
if ( ! file_exists( JM_TC_DIR . 'vendor/autoload.php' ) ) {
	return false;
}

require_once ( JM_TC_DIR . 'vendor/autoload.php' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jm_tc_settings_action_link' );

/**
 * Re-add Settings link to admin page
 * some users needed it and it does not evil ^^
 * @param $links
 *
 * @return mixed
 */
function jm_tc_settings_action_link( $links ) {
	$links['settings'] = '<a href="' . add_query_arg( array( 'page' => 'jm_tc' ), admin_url( 'admin.php') ) . '">' . __( 'Settings' ) . '</a>';
	return $links;
}

register_activation_hook( __FILE__, array( 'TokenToMe\TwitterCards\Admin\Init', 'activate' ) );

add_action(
	'plugins_loaded',
	array ( JM_TC_Loading::get_instance(), 'plugin_setup' )
);
class JM_TC_Loading {
	/**
	 * Plugin instance.
	 * @type object
	 */
	protected static $instance = NULL;

	/**
	 * Access this plugin's working instance
	 *
	 * @wp-hook plugins_loaded
	 * @return $this object (kidding)
	 */
	public static function get_instance() {
		NULL === self::$instance and self::$instance = new self;
		return self::$instance;
	}
	/**
	 * Setup
	 * @return  void
	 */
	public function plugin_setup() {

		if ( is_admin() ) {
			new TokenToMe\TwitterCards\Admin\Main();
			new TokenToMe\TwitterCards\Admin\ImportExport();
			new TokenToMe\TwitterCards\Admin\Box();
		} else {
			new TokenToMe\TwitterCards\Thumbs();
		}
	}
	/**
	 * Constructor. Intentionally left empty and public.
	 *
	 * @see plugin_setup()
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'add_markup' ), 1 );
		add_action( 'init', array( $this, 'on_init' ), 1 );
	}

	/**
	 * Everything that triggers on this hook
	 * init
	 */
	public function on_init(){
		$this->register_text_domain( 'jm-tc' );
	}

	/**
	 * Add specific markup
	 */
	public function add_markup(){
		if ( ! is_404() && ! is_tag() && ! is_archive() && ! is_tax() && ! is_category() ) {
			$factory = new TokenToMe\TwitterCards\Factory();
			$factory->createMarkup( get_queried_object_id() );
		}
	}

	/**
	 * Loads translations
	 *
	 * @param   string $domain
	 * @return  void
	 */
	public function register_text_domain( $domain ){
		load_plugin_textdomain(
			$domain,
			FALSE,
			plugin_basename( dirname( __FILE__ ) ) . '/languages'
		);
	}
}