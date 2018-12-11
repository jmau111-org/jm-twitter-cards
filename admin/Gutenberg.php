<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utils;
use TokenToMe\TwitterCards\Thumbs;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Gutenberg {

	protected $plugin_name;
	protected $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * @author Julien Maury
	 */
	public function scripts_enqueue() {

		$js_file_path  = JM_TC_DIR . 'admin/js/cards/build/index.js';
		$css_file_path = JM_TC_DIR . 'admin/js/cards/build/style.css';

		wp_register_script(
			'tc-gut-sidebar',
			JM_TC_URL . 'js/cards/build/index.js',
			[ 'wp-i18n' ],
			filemtime( $js_file_path ),
			true
		);

		wp_register_style(
			'tc-gut-styles',
			JM_TC_URL . 'js/cards/build/style.css',
			[ 'wp-edit-blocks' ],
			filemtime( $css_file_path )
		);

		wp_localize_script(
			'tc-gut-sidebar',
			'tcData',
			[
				'twitterSite'  => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterSite' ),
				'domain'       => ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : get_bloginfo( 'url' ),
				'avatar'       => get_avatar_url( 0, 16 ),
				'defaultImage' => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterImage' ),
				'defaultType'  => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterCardType' ),
				'pluginUrl'    => JM_TC_URL,
			]
		);

		/*
		 * Pass already loaded translations to our JavaScript.
		 *
		 * This happens _before_ our JavaScript runs, afterwards it's too late.
		 */
		wp_add_inline_script(
			'tc-gut-sidebar',
			'wp.i18n.setLocaleData( ' . json_encode( $this->get_jed_locale_data( 'jm-tc-gut' ) ) . ', "jm-tc-gut" );',
			'before'
		);


		wp_enqueue_script( 'tc-gut-sidebar' );
		wp_enqueue_style( 'tc-gut-styles' );

	}

	/**
	 * @param $domain
	 *
	 * @return array
	 */
	protected function get_jed_locale_data( $domain ) {
		$translations = get_translations_for_domain( $domain );

		$locale = array(
			'' => array(
				'domain' => $domain,
				'lang'   => is_admin() ? get_user_locale() : get_locale(),
			),
		);

		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}

	public function load_i18n() {

		load_plugin_textdomain( 'jm-tc-gut', false, JM_TC_LANG_DIR );

	}

}