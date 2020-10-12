<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Utils;

if (!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

class Gutenberg
{
	/**
	 * @author unknown
	 */
	public function register_scripts()
	{
		if (!is_admin()) {
			return;
		}

		$rel_path_css = 'build/style-index.css';
		$rel_path_js = 'build/index.js';

		if (!file_exists(JM_TC_DIR . $rel_path_js) || !file_exists(JM_TC_DIR . $rel_path_css)) {
			return;
		}

		wp_register_script(
			'tc-gut-sidebar',
			JM_TC_URL . $rel_path_js,
			[],
			Utils::assets_version($rel_path_js),
			true
		);
		
		wp_localize_script(
			'tc-gut-sidebar',
			'tcData',
			[
				'twitterSite'  => Utils::remove_at(Utils::maybe_get_opt(jm_tc_get_options(), 'twitterSite')),
				'domain'       => !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : get_bloginfo('url'),
				'avatar'       => get_avatar_url(0, 16),
				'defaultImage' => Utils::maybe_get_opt(jm_tc_get_options(), 'twitterImage'),
				'defaultType'  => Utils::maybe_get_opt(jm_tc_get_options(), 'twitterCardType'),
				'pluginUrl'    => JM_TC_URL,
			]
		);
		
		/**
		 * @see https://developer.wordpress.org/block-editor/developers/internationalization/
		 */
		wp_set_script_translations(
			'tc-gut-sidebar', 
			'jm-tc-gut', 
			plugin_dir_path( dirname( __FILE__ ) ) . "languages"
		);

		wp_register_style(
			'tc-gut-styles',
			JM_TC_URL . $rel_path_css,
			['wp-edit-blocks'],
			Utils::assets_version($rel_path_css)
		);
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script('tc-gut-sidebar');
		wp_enqueue_style('tc-gut-styles');
	}
}
