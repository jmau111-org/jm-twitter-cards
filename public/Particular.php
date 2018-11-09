<?php

namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Particular {

	protected $plugin_name;
	protected $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * filter for robots.txt rules
	 *
	 * @param $output
	 *
	 * @since 5.3.2
	 * @return string
	 */
	public static function robots_mod( $output ) {

		$output .= 'User-agent: Twitterbot' . PHP_EOL;
		$output .= 'Disallow: ';

		return $output;
	}

	/**
	 * @param $meta
	 *
	 * @return bool
	 */
	public function remove_myself( $meta ) {

		if ( in_array( strtolower( $meta ), [ 'tweetpressfr', '@tweetpressfr', 'jmau111', '@jmau111' ], true ) ) {
			return false;
		}

		return $meta;
	}

	/**
	 * Default options for multisite when creating new site
	 *
	 * @param $blog_id
	 */
	public function new_blog( $blog_id ) {
		switch_to_blog( $blog_id );
		Init::on_activation();
		restore_current_blog();
	}

	/**
	 * alter excerpt with filter jm_tc_get_excerpt
	 * @since 5.3.2
	 * @return string
	 */
	function modify_excerpt( $excerpt ) {
		global $post;
		$_excerpt = $this->get_excerpt_from_far_far_away( $post->ID );

		if ( ! empty( $_excerpt ) ) {
			return $_excerpt;
		}

		return $excerpt;
	}

	/**
	 * Get excerpt from database
	 * @since 5.3.2
	 * @return string
	 */
	function get_excerpt_from_far_far_away( $post_id ) {
		global $wpdb;
		$query        = "SELECT post_excerpt FROM {$wpdb->posts} WHERE ID = %d LIMIT 1";
		$result       = $wpdb->get_results( $wpdb->prepare( $query, (int) $post_id ), ARRAY_A );
		$post_excerpt = $result[0]['post_excerpt'];

		return esc_attr( $post_excerpt );
	}
}