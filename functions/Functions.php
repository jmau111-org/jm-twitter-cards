<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Globalize options
 * provides filter for options
 * @return array
 */

if ( ! function_exists( 'jm_tc_get_options' ) ) {
	function jm_tc_get_options() {
		global $jm_tc_options;
		$jm_tc_options = get_option( JM_TC_SLUG_MAIN_OPTION );
		$jm_tc_options = apply_filters( 'jm_tc_get_options', $jm_tc_options );

		return $jm_tc_options;
	}
}

if ( ! function_exists( 'jm_tc_remove_at' ) ) {
	/**
	 * @param $at
	 *
	 * @return bool|mixed
	 */
	function jm_tc_remove_at( $at ) {
		return \TokenToMe\TwitterCards\Utils::remove_at( $at );
	}
}

if ( ! function_exists( 'jm_tc_remove_lb' ) ) {
	/**
	 * @param $lb
	 *
	 * @return string
	 */
	function jm_tc_remove_lb( $lb ) {
		return \TokenToMe\TwitterCards\Utils::remove_lb( $lb );
	}
}

if ( ! function_exists( 'jm_tc_get_excerpt_by_id' ) ) {
	/**
	 * @param $post_id
	 *
	 * @return string
	 */
	function jm_tc_get_excerpt_by_id( $post_id ) {
		return \TokenToMe\TwitterCards\Utils::get_excerpt_by_id( $post_id );
	}
}

/**
 * Utils::gutenberg_exists()
 */
if ( ! function_exists( 'jm_tc_gutenberg_exists' ) ) {
	function jm_tc_gutenberg_exists() {
		return \TokenToMe\TwitterCards\Utils::gutenberg_exists();
	}
}