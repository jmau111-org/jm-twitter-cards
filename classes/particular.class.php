<?php
namespace TokenToMe\TwitterCards;

if ( ! defined( 'JM_TC_VERSION' ) ) {
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

		add_filter( 'robots_txt', array( $this, 'robots_mod' ), 10, 2 );
		add_filter( 'cmb_meta_box_url', array( $this, 'update_cmb_meta_box_url' ) );

		$this->opts = jm_tc_get_options();

		if ( isset( $this->opts['twitterCardExcerpt'] ) && 'yes' === $this->opts['twitterCardExcerpt'] ) {
			add_filter( 'jm_tc_get_excerpt', array( $this, 'modify_excerpt' ) );
		}

		add_action( 'wpmu_new_blog', array( $this, 'new_blog' ) );

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
	 * SSL stuffs
	 * @since 5.3.2
	 * @return string
	 */
	public static function update_cmb_meta_box_url( $url ) {
		if ( is_ssl() ) {
			// modify the url here
			return preg_replace( '|^http://|', 'https://', $url );
		}

		return $url;

	}


	/**
	 * filter for robots.txt rules
	 * @since 5.3.2
	 * @return string
	 */
	public static function robots_mod( $output, $public ) {

		$opts = jm_tc_get_options();

		if ( 'yes' === $opts['twitterCardRobotsTxt'] ) {
			$output .= 'User-agent: Twitterbot' . PHP_EOL;
			$output .= 'Disallow: ';
		}

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