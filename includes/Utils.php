<?php

namespace TokenToMe\TwitterCards;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Utils {

	/**
	 * @param $at
	 *
	 * @return bool|mixed
	 */
	public static function remove_at( $at ) {

		if ( ! is_string( $at ) ) {
			return false;
		}

		$noat = str_replace( '@', '', $at );

		return $noat;
	}

	/**
	 * Put some cache on request
	 * @return bool|mixed
	 * @author Julien Maury
	 */
	public static function get_github_repositories() {
		$data = get_site_transient( 'jm_github_repos' );
		if ( empty( $data ) ) {

			$request = wp_remote_get( 'https://api.github.com/users/jmau111/repos?sort=created' );

			if ( ! empty( $request ) && ! is_wp_error( $request ) && wp_remote_retrieve_response_code( $request ) === 200 ) {
				$data = wp_remote_retrieve_body( $request );
				set_site_transient( 'jm_github_repos', $data, WEEK_IN_SECONDS );// it's actually enough ^^
			}

		}

		return $data;
	}


	/**
	 * @param $lb
	 *
	 * @return string
	 */
	public static function remove_lb( $lb ) {
		$output = str_replace( [ "\r" . PHP_EOL, "\r" ], PHP_EOL, $lb );
		$lines  = explode( PHP_EOL, $output );
		$nolb   = [];
		foreach ( $lines as $key => $line ) {
			if ( ! empty( $line ) ) {
				$nolb[] = trim( $line );
			}
		}

		return implode( $nolb );
	}

	/**
	 * @param $post_id
	 *
	 * @return string
	 */
	public static function get_excerpt_by_id( $post_id ) {
		$the_post    = get_post( $post_id );
		$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

		//kill shortcode
		$shortcode_pattern = get_shortcode_regex();
		$the_excerpt       = preg_replace( '/' . $shortcode_pattern . '/', '', $the_excerpt );

		// kill tags
		$the_excerpt = strip_tags( $the_excerpt );

		return esc_attr( substr( $the_excerpt, 0, 200 ) ); // to prevent meta from being broken by e.g ""
	}

	/**
	 * Allows us to get post types we want
	 * and make some show/hide
	 * @return array
	 */
	public static function get_post_types() {
		$cpts = get_option( 'jm_tc_cpt' );

		return empty( $cpts['twitterCardPt'] ) ? get_post_types( [ 'public' => true ] ) : array_values( $cpts['twitterCardPt'] );
	}

	/**
	 * @return bool
	 * @author Julien Maury
	 */
	public static function gutenberg_exists() {
		return function_exists( 'the_gutenberg_project' );
	}

	/**
	 * @return array
	 * @author Julien Maury
	 */
	public static function get_default_options() {
		return [
			'twitterCardType'       => 'summary',
			'twitterCreator'        => '',
			'twitterSite'           => '',
			'twitterImage'          => 'https://g.twimg.com/Twitter_logo_blue.png',
			'twitterImageAlt'       => '',
			'twitterCardImgSize'    => 'web',
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
			'twitterCardRobotsTxt'  => 'no',
			'twitterAppCountry'     => '',
			'twitterCardOg'         => 'no',
		];
	}

	/**
	 * @return bool
	 * @author Julien Maury
	 */
	public static function is_dev_env() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}

	/**
	 * @return bool
	 * @author Julien Maury
	 */
	public static function suffix_for_dev_env() {
		return self::is_dev_env() ? '' : '.min';
	}

	/**
	 * @param $array
	 * @param $key
	 *
	 * @return string
	 */
	public static function maybe_get_opt( $array, $key ) {

		if ( empty( $array ) ) {
			return '';
		}

		return array_key_exists( $key, $array ) ? $array[ $key ] : '';
	}
}