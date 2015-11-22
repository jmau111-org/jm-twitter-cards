<?php
namespace TokenToMe\TwitterCards;
use TokenToMe\TwitterCards\Admin\Init;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Particular {
	/**
	 * Options
	 * @var array $opts
	 */
	protected $opts = array();

	/**
	 * Constructor
	 * @since 5.3.2
	 */
	public function __construct() {

		add_filter( 'robots_txt', array( $this, 'robots_mod' ) );

		$this->opts = \jm_tc_get_options();

		if ( isset( $this->opts['twitterCardExcerpt'] ) && 'yes' === $this->opts['twitterCardExcerpt'] ) {
			add_filter( 'jm_tc_get_excerpt', array( $this, 'modify_excerpt' ) );
		}

		add_action( 'wpmu_new_blog', array( $this, 'new_blog' ) );

		add_filter( 'jm_tc_card_site', array( $this, 'remove_tweetpressfr') );
		add_filter( 'jm_tc_card_creator', array( $this, 'remove_tweetpressfr') );

	}

	/**
	 * @since 6.1
	 * Use my own filters to fix my mess !
	 */
	public function remove_tweetpressfr( $meta ){

		if ( 'tweetpressfr' === strtolower( $meta ) || '@tweetpressfr' === strtolower( $meta ) ){
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
	 * filter for robots.txt rules
	 *
	 * @param $output
	 * @since 5.3.2
	 * @return string
	 */
	public static function robots_mod( $output ) {

		$output .= 'User-agent: Twitterbot' . PHP_EOL;
		$output .= 'Disallow: ';

		return $output;
	}

	/**
	 * alter excerpt with filter jm_tc_get_excerpt
	 * @since 5.3.2
	 * @return string
	 */
	function modify_excerpt() {
		global $post;
		return $this->get_excerpt_from_far_far_away( $post->ID );
	}

	/**
	 * Get excerpt from database
	 * @since 5.3.2
	 * @return string
	 */
	function get_excerpt_from_far_far_away( $post_id ) {
		global $wpdb;
		$query        = "SELECT post_excerpt FROM {$wpdb->posts} WHERE ID = %d LIMIT 1";
		$result       = $wpdb->get_results( $wpdb->prepare( $query, $post_id ), ARRAY_A );
		$post_excerpt = $result[0]['post_excerpt'];

		return esc_attr( $post_excerpt );
	}
}