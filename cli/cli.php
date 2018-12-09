<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

WP_CLI::add_command( JM_TC_SLUG_MAIN_OPTION, 'JM_TC_CLI' );

/**
 * Control your install
 */
class JM_TC_CLI extends WP_CLI_Command {

	/**
	 * Set username
	 * @author Julien Maury
	 *
	 * ## EXAMPLES
	 *
	 * wp jm_tc set_username rihanna
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function set_username( $args, $assoc_args ) {

		if ( empty( $args[0] ) ) {
			WP_CLI::error( __( 'You sox !', 'jm-tc' ) );
		}

		$options                   = get_option( JM_TC_SLUG_MAIN_OPTION );
		$options['twitterCreator'] = jm_tc_remove_at( $args[0] );
		update_option( JM_TC_SLUG_MAIN_OPTION, $options );

		WP_CLI::success( __( 'Twitter Cards creator set successfully', 'jm-tc' ) );

	}

	/**
	 * Set sitename
	 * @author Julien Maury
	 *
	 * ## EXAMPLES
	 *
	 * wp jm_tc set_sitename nbcnews
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function set_sitename( $args, $assoc_args ) {

		if ( empty( $args[0] ) ) {
			WP_CLI::error( __( 'You sox !', 'jm-tc' ) );
		}

		$options                = get_option( JM_TC_SLUG_MAIN_OPTION );
		$options['twitterSite'] = jm_tc_remove_at( $args[0] );
		update_option( JM_TC_SLUG_MAIN_OPTION, $options );

		WP_CLI::success( __( 'Twitter Cards Sitename set successfully', 'jm-tc' ) );

	}

	/**
	 * Set card type
	 * @author Julien Maury
	 *
	 * ## EXAMPLES
	 *
	 * wp jm_tc set_sitename nbcnews
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function set_cardtype( $args, $assoc_args ) {

		if ( empty( $args[0] ) || ! in_array( $args[0], [
				'summary',
				'summary_large_image',
				'app',
				'player',
			] ) ) {
			WP_CLI::error( __( 'You sox !', 'jm-tc' ) );
		}

		if ( 'player' === $args[0] ) {
			WP_CLI::error( __( 'Player cards cannot be set globally, makes no sense, just go to the metabox please. You sox a little bit !', 'jm-tc' ) );
		}

		$options                    = get_option( 'jm_tc' );
		$options['twitterCardType'] = jm_tc_remove_at( $args[0] );
		update_option( JM_TC_SLUG_MAIN_OPTION, $options );

		WP_CLI::success( __( 'Twitter Cards Type set successfully', 'jm-tc' ) );

	}

	/**
	 * Set opengraph
	 * @author Julien Maury
	 *
	 * ## EXAMPLES
	 *
	 * wp jm_tc set_opengraph yes
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function set_opengraph( $args, $assoc_args ) {

		if ( empty( $args[0] ) || ! in_array( $args[0], [ 'yes', 'no' ] ) ) {
			WP_CLI::error( __( 'You gotta be kidding me !', 'jm-tc' ) );
		}

		$options                  = get_option( 'jm_tc' );
		$options['twitterCardOg'] = $args[0];
		update_option( JM_TC_SLUG_MAIN_OPTION, $options );

		WP_CLI::success( __( 'Twitter Cards creator set successfully', 'jm-tc' ) );

	}

	/**
	 * Set post types for metabox
	 * @author Julien Maury
	 *
	 * ## EXAMPLES
	 *
	 * wp jm_tc set_post_types post page movie
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function set_post_types( $args, $assoc_args ) {

		if ( empty( $args[0] ) ) {
			WP_CLI::error( __( 'You sox !', 'jm-tc' ) );
		}

		$_pts = [];

		foreach ( $args as $arg ) {

			if ( ! post_type_exists( $arg ) ) {
				WP_CLI::error( sprintf( __( '%s is not a valid post type ! And you still sox !', 'jm-tc' ) ) );
				break;
			}


			$_pts['twitterCardPt'][ $arg ] = $arg;
		}

		update_option( JM_TC_SLUG_CPT_OPTION, $_pts );

		WP_CLI::success( __( 'Twitter Cards custom post types set successfully', 'jm-tc' ) );
	}

}