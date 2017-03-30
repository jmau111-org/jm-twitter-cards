<?php
namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Loading {
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
			new Admin\Main();
			new Admin\ImportExport();
			new Admin\Box();
		} else {
			new Thumbs();
			new Particular();
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
			$factory = new Factory();
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
