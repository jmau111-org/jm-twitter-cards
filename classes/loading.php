<?php

namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Loading {

	/**
	 * Setup
	 * @return  void
	 */
	public function plugin_setup() {

		if ( is_admin() ) {
			new Admin\Main();
			new Admin\ImportExport();
			new Admin\Metabox();
		}

		new Thumbs();
		new Particular();
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
	public function on_init() {
		$this->register_text_domain( 'jm-tc' );
	}

	/**
	 * Add specific markup
	 */
	public function add_markup() {

		if ( ! is_home() && ! is_front_page() && ! in_array( get_post_type(), Utilities::get_post_types(), true ) ) {
			return false;
		}

		if ( ! is_404() && ! is_tag() && ! is_archive() && ! is_tax() && ! is_category() ) {
			$factory = new Factory();
			$factory->generateMarkup( get_queried_object_id() );
		}

		return true;
	}

	/**
	 * Loads translations
	 *
	 * @param   string $domain
	 *
	 * @return  void
	 */
	public function register_text_domain( $domain ) {
		load_plugin_textdomain( $domain, false, JM_TC_LANG_DIR );
	}
}