<?php
namespace TokenToMe\TwitterCards;

if ( ! function_exists( 'add_action' ) ) {
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
	protected $options;

	public function __construct( Admin\Options $options ) {

		$this->opts = \jm_tc_get_options();
		$this->options = $options;
		add_action( 'wp_head', array( $this, 'add_markup' ), 1 );

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

	public function add_markup() {

		if ( is_singular()
		     && ! is_front_page()
		     && ! is_home()
		     && ! is_404()
		     && ! is_tag()
		) {

			// safer than the global $post => seems killed on a lot of install :/
			$post_ID = get_queried_object()->ID;

			$this->html_comments();

			/* most important meta */
			$this->display( $this->options->card_type( $post_ID ) );
			$this->display( $this->options->creator_username( true ) );
			$this->display( $this->options->site_username() );
			$this->display( $this->options->title( $post_ID ) );
			$this->display( $this->options->description( $post_ID ) );
			$this->display( $this->options->image( $post_ID ) );

			/* secondary meta */
			$this->display( $this->options->card_dim( $post_ID ) );
			$this->display( $this->options->player( $post_ID ) );
			$this->display( $this->options->deep_linking() );

			$this->html_comments( true );
		}

		if ( is_home() || is_front_page() ) {

			$this->html_comments();

			$this->display( $this->options->card_type() );
			$this->display( $this->options->site_username() );
			$this->display( $this->options->creator_username() );
			$this->display( $this->options->title() );
			$this->display( $this->options->description() );
			$this->display( $this->options->image() );
			$this->display( $this->options->card_dim() );
			$this->display( $this->options->deep_linking() );

			$this->html_comments( true );
		}

	}

	/**
	 * @param $data
	 */
	protected function display( $data ) {

		if ( is_array( $data ) ) {

			/**
			 * Values are filerable
			 * so we need to sanitize again
			 */
			$data = array_map( 'esc_attr', $data );
			foreach ( $data as $name => $value ) {
				if ( '' !== $value ) {
					if ( 'yes' === $this->opts['twitterCardOg'] && in_array( $name, array(
							'title',
							'description',
							'image',
							'image:width',
							'image:height',
						) )
					) {
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

