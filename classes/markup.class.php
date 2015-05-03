<?php
namespace TokenToMe\twitter_cards;

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


class Markup {
	/**
	 * Options
	 * @var array
	 */
	protected $opts = array();

	/**
	 * Constructor
	 * @since 5.3.2
	 */
	function __construct() {

		$this->opts = jm_tc_get_options();

	}

	/**
	 * Add just one line before meta
	 * @since 5.3.2
	 *
	 * @param bool $end
	 *
	 * @return string
	 */
	public function html_comments( $end = false ) {

		if ( false === $end ) {
			echo PHP_EOL . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . PHP_EOL;
		} else {
			echo '<!-- /JM Twitter Cards ' . JM_TC_VERSION . ' -->' . PHP_EOL . PHP_EOL;
		}
	}


	/**
	 *
	 */
	public function add_markup() {

		$GLOBALS['jm_twitter_cards']['options'] = new Options;
		$options = $GLOBALS['jm_twitter_cards']['options'];

		if ( is_singular()
			&& ! is_front_page()
			&& ! is_home()
			&& ! is_404()
			&& ! is_tag() ) {

			// safer than the global $post => seems killed on a lot of install :/
			$post_ID = get_queried_object()->ID;

			$this->html_comments();

			/* most important meta */
			$this->display_markup( $options->card_type( $post_ID ) );
			$this->display_markup( $options->creator_username( true ) );
			$this->display_markup( $options->site_username() );
			$this->display_markup( $options->title( $post_ID ) );
			$this->display_markup( $options->description( $post_ID ) );
			$this->display_markup( $options->image( $post_ID ) );

			/* secondary meta */
			$this->display_markup( $options->card_dim( $post_ID ) );
			$this->display_markup( $options->product( $post_ID ) );
			$this->display_markup( $options->player( $post_ID ) );
			$this->display_markup( $options->deep_linking() );

			$this->html_comments( true );
		}

		if ( is_home() || is_front_page() ) {

			$this->html_comments();

			$this->display_markup( $options->card_type() );
			$this->display_markup( $options->site_username() );
			$this->display_markup( $options->creator_username() );
			$this->display_markup( $options->title() );
			$this->display_markup( $options->description() );
			$this->display_markup( $options->image() );
			$this->display_markup( $options->card_dim() );
			$this->display_markup( $options->deep_linking() );

			$this->html_comments( true );
		}

	}

	/**
	 * @param $data
	 */
	protected function display_markup( $data ) {

		if ( is_array( $data ) ) {
			foreach ( $data as $name => $value ) {
				if ( '' !== $value ) {
					if ( 'yes' === $this->opts['twitterCardOg'] && in_array( $name, array(
							'title',
							'description',
							'image',
							'image:width',
							'image:height',
						) ) ) {
						$is_og    = 'og';
						$name_tag = 'property';
					} else {
						$is_og    = 'twitter';
						$name_tag = 'name';
					}
					echo '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
				}
			}
		} elseif ( is_string( $data ) ) {
			echo '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . PHP_EOL;
		}
	}
}


