<?php

namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Init {

	/**
	 * Avoid undefined index by registering default options
	 */
	public static function on_activation() {
		$opts = get_option( JM_TC_SLUG_MAIN_OPTION );
		if ( ! is_array( $opts ) ) {
			update_option( JM_TC_SLUG_MAIN_OPTION, self::get_default_options() );
		}
	}

	/**
	 * Return default options
	 * @return array
	 */
	public static function get_default_options() {
		return array(
			'twitterCardType'       => 'summary',
			'twitterCreator'        => '',
			'twitterSite'           => '',
			'twitterImage'          => '',
			'twitterImageAlt'       => '',
			'twitterProfile'        => 'yes',
			'twitterPostPageTitle'  => get_bloginfo( 'name' ), // filter used by plugin to customize title
			'twitterPostPageDesc'   => __( 'Welcome to', 'jm-tc' ) . ' ' . get_bloginfo( 'name' ) . ' - ' . __( 'see blog posts', 'jm-tc' ),
			'twitterCardTitle'      => '',
			'twitterCardDesc'       => '',
			'twitterCardExcerpt'    => 'no',
			'twitterUsernameKey'    => 'jm_tc_twitter',
			'twitteriPhoneName'     => '',
			'twitteriPadName'       => '',
			'twitterGooglePlayName' => '',
			'twitteriPhoneUrl'      => '',
			'twitteriPadUrl'        => '',
			'twitterGooglePlayUrl'  => '',
			'twitteriPhoneId'       => '',
			'twitteriPadId'         => '',
			'twitterGooglePlayId'   => '',
			'twitterCardRobotsTxt'  => 'yes',
			'twitterAppCountry'     => '',
			'twitterCardOg'         => 'no',
		);
	}

	/**
	 * Avoid undefined index by registering default options
	 */
	public static function activate() {
		if ( ! is_multisite() ) {
			self::on_activation();
		}
	}
}