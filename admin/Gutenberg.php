<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utils;

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
	 * Returns Jed-formatted localization data.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $domain Translation domain.
	 *
	 * @return array
	 */
	public function get_jed_locale_data( $domain ) {
		$translations = get_translations_for_domain( $domain );
		$locale       = [
			'' => [
				'domain' => $domain,
				'lang'   => is_admin() ? get_user_locale() : get_locale(),
			],
		];
		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}
		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}

	/**
	 * Registers the i18n script
	 *
	 * @since 1.0.0
	 */
	public function i18n_register() {

		$locale_data = $this->get_jed_locale_data( 'jm-tc' );
		$content     = 'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ', "jm-tc" );';

		wp_register_script(
			'jm-tc-gut-i18n',
			JM_TC_URL . 'js/i18n/build/index.js',
			[],
			filemtime( JM_TC_DIR . 'admin/js/i18n/build/index.js' )
		);
		wp_add_inline_script( 'jm-tc-gut-i18n', $content );
	}

	/**
	 * Registers the alerts script
	 * @author Julien Maury
	 * @since 1.0.0
	 */
	public function script_register() {

		wp_register_script(
			'jm-tc-gut-metabox',
			JM_TC_URL . 'js/cards/build/index.js',
			[
				'jm-tc-gut-i18n',
			],
			filemtime( JM_TC_DIR . 'admin/js/cards/build/index.js' ),
			true
		);
		wp_register_style(
			'jm-tc-gut-metabox',
			JM_TC_URL . 'js/cards/build/style.css',
			[],
			filemtime( JM_TC_DIR . 'admin/js/cards/build/style.css' )
		);

		wp_localize_script(
			'jm-tc-gut-metabox',
			'tcDataMetabox',
			[
				'twitterSite'  => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterSite' ),
				'domain'       => ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : get_bloginfo( 'url' ),
				'avatar'       => get_avatar_url( 0, 16 ),
				'defaultImage' => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterImage' ),
				'defaultType'  => Utils::maybe_get_opt( jm_tc_get_options(), 'twitterCardType' ),
				'pluginUrl'    => JM_TC_URL,
			]
		);
	}

	/**
	 * @author Julien Maury
	 */
	public function script_enqueue() {

		if ( ! in_array( get_post_type(), Utils::get_post_types(), true ) ) {
			return false;
		}

		wp_enqueue_script( 'jm-tc-gut-metabox' );
		wp_enqueue_style( 'jm-tc-gut-metabox' );
	}

}