<?php

namespace TokenToMe\TwitterCards;

use TokenToMe\TwitterCards\Admin\Admin;
use TokenToMe\TwitterCards\Admin\Gutenberg;
use TokenToMe\TwitterCards\Admin\Metabox;

if ( ! defined( 'JM_TC_SLUG_MAIN_OPTION' ) ) {
	define( 'JM_TC_SLUG_MAIN_OPTION', 'jm_tc' );
}

if ( ! defined( 'JM_TC_SLUG_CPT_OPTION' ) ) {
	define( 'JM_TC_SLUG_CPT_OPTION', 'jm_tc_cpt' );
}

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Main {

	protected $loader;
	protected $opts;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'JM Twitter Cards';
		$this->version     = JM_TC_VERSION;

		$this->load_dependencies();
		$this->define_post_hooks();

		if ( is_admin() ) { // I want this
			$this->define_admin_hooks();
		} else {
			$this->define_public_hooks();
		}

		$this->opts = \jm_tc_get_options();
	}

	/**
	 * @author Julien Maury
	 */
	protected function load_dependencies() {

		require_once JM_TC_DIR . 'includes/Init.php';
		require_once JM_TC_DIR . 'includes/Loader.php';

		require_once JM_TC_DIR . 'includes/Utils.php';

		require_once JM_TC_DIR . 'functions/Functions.php';

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once JM_TC_DIR . 'cli/cli.php';
		}

		if ( Utils::gutenberg_exists() ) {
			require_once JM_TC_DIR . 'admin/Gutenberg.php';
		} else {
			require_once JM_TC_DIR . 'admin/Fields.php';
			require_once JM_TC_DIR . 'admin/Metabox.php';
		}

		require_once JM_TC_DIR . 'admin/Admin.php';
		require_once JM_TC_DIR . 'admin/Settings.php';
		require_once JM_TC_DIR . 'admin/Options.php';
		require_once JM_TC_DIR . 'admin/Meta.php';

		require_once JM_TC_DIR . 'public/Front.php';
		require_once JM_TC_DIR . 'public/Thumbs.php';
		require_once JM_TC_DIR . 'public/Particular.php';

		$this->loader = new Loader();
	}


	/**
	 * Register all of the hooks related to the map post type
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_post_hooks() {
		if ( ! Utils::gutenberg_exists() ) {
			$plugin_posts = new Metabox( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'add_meta_boxes', $plugin_posts, 'add_box' );
			$this->loader->add_action( 'save_post', $plugin_posts, 'save_box', 10, 2 );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_posts, 'admin_enqueue_scripts' );
		} else {
			$plugin_posts = new Meta( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'init', $plugin_posts, 'gutenberg_register_meta' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_public_hooks() {
		$plugin_front = new Front( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_head', $plugin_front, 'add_markup', 0 );

		$plugin_front = new Thumbs( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'admin_post_thumbnail_html', $plugin_front, 'add_featured_image_instruction' );

		$plugin_front = new Particular( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'robots_txt', $plugin_front, 'robots_mod' );

		if ( isset( $this->opts['twitterCardExcerpt'] ) && 'yes' === $this->opts['twitterCardExcerpt'] ) {
			$this->loader->add_filter( 'jm_tc_get_excerpt', $plugin_front, 'modify_excerpt' );
		}

		$this->loader->add_action( 'wpmu_new_blog', $plugin_front, 'new_blog' );
		$this->loader->add_filter( 'jm_tc_card_site', $plugin_front, 'remove_myself' );
		$this->loader->add_filter( 'jm_tc_card_creator', $plugin_front, 'remove_myself' );
	}

	/**
	 * Register all of the hooks related to the admin functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_admin_hooks() {

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'admin_enqueue_scripts' );
		$this->loader->add_filter( 'plugin_action_links_' . JM_TC_BASENAME, $plugin_admin, 'settings_action_link' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process_settings_export' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process_settings_import' );

		$post_type = get_post_type();

		if ( ! isset( $_GET['post_type'] ) ) {
			$post_type = 'post';
		} elseif ( in_array( $_GET['post_type'], get_post_types( [ 'show_ui' => true ] ) ) ) {
			$post_type = $_GET['post_type'];
		}

		if ( Utils::gutenberg_exists() && in_array( $post_type, Utils::get_post_types(), true ) ) {
			$gut = new Gutenberg( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'enqueue_block_editor_assets', $gut, 'scripts_enqueue' );
			$this->loader->add_action( 'admin_init', $gut, 'load_i18n' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}