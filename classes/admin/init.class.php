<?php
namespace TokenToMe\TwitterCards\Admin;

//Add some security, no direct load !
defined( 'ABSPATH' )
or die( 'No direct load !' );

class Init {

	/**
	 * Avoid undefined index by registering default options
	 */
	public static function on_activation() {
		$opts = get_option( 'jm_tc' );
		if ( ! is_array( $opts ) ) {
			update_option( 'jm_tc', self::get_default_options() );
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
			'twitterImage'          => 'https://g.twimg.com/Twitter_logo_blue.png',
			'twitterCardImgSize'    => 'small',
			'twitterImageWidth'     => '280',
			'twitterImageHeight'    => '150',
			'twitterCardMetabox'    => 'yes',
			'twitterProfile'        => 'yes',
			'twitterPostPageTitle'  => get_bloginfo( 'name' ), // filter used by plugin to customize title
			'twitterPostPageDesc'   => __( 'Welcome to', 'jm-tc' ) . ' ' . get_bloginfo( 'name' ) . ' - ' . __( 'see blog posts', 'jm-tc' ),
			'twitterCardTitle'      => '',
			'twitterCardDesc'       => '',
			'twitterCardExcerpt'    => 'no',
			'twitterCardCrop'       => 'yes',
			'twitterCardCropX'      => '',
			'twitterCardCropY'      => '',
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
			'twitterCardRobotsTxt'  => 'no',
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
