<?php

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Globalize options
 * provides filter for options
 * @return $jm_tc_options
 */

if ( ! function_exists( 'jm_tc_get_options' ) ) {
	function jm_tc_get_options() {
		global $jm_tc_options;
		$jm_tc_options = get_option( 'jm_tc' );
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
		return \TokenToMe\TwitterCards\Utilities::remove_at( $at );
	}
}

if ( ! function_exists( 'jm_tc_remove_lb' ) ) {
	/**
	 * @param $lb
	 *
	 * @return string
	 */
	function jm_tc_remove_lb( $lb ) {
		return \TokenToMe\TwitterCards\Utilities::remove_lb( $lb );
	}
}

if ( ! function_exists( 'jm_tc_get_excerpt_by_id' ) ) {
	/**
	 * @param $post_id
	 *
	 * @return string|void
	 */
	function jm_tc_get_excerpt_by_id( $post_id ) {
		return \TokenToMe\TwitterCards\Utilities::get_excerpt_by_id( $post_id );
	}
}