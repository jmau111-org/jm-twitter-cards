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
	protected $options = array();

	public function __construct( Admin\Options $options ) {

		$this->options = $options;
		$this->opts    = \jm_tc_get_options();
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
		$slash = ( false === $end ) ? '' : '/';
		return '<!-- ' . $slash . 'JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . PHP_EOL;
	}

	public function generateMarkup() {

		echo $this->html_comments();

		/* most important meta */
		echo $this->build( $this->options->card_type() );
		echo $this->build( $this->options->creator_username( true ) );
		echo $this->build( $this->options->site_username() );
		echo $this->build( $this->options->title() );
		echo $this->build( $this->options->description() );
		echo $this->build( $this->options->image() );

		/* secondary meta */
		echo $this->build( $this->options->image_alt() );
		echo $this->build( $this->options->player() );
		echo $this->build( $this->options->deep_linking() );

		echo $this->html_comments( true );
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function build( $data ) {

		$markup = '';

		if ( is_array( $data ) ) {

			/**
			 * Values are filterable
			 * so we need to sanitize again
			 */
			$data = array_map( 'esc_attr', $data );
			$data = array_filter( $data );

			foreach ( $data as $name => $value ) {
				if ( '' !== $value ) {

					if ( '@' === $value ) {
						$markup = '<!-- [(-_-)@@(-_-)] -->' . PHP_EOL;
						continue;
					}

					if ( ! empty( $this->opts['twitterCardOg'] ) && 'yes' === $this->opts['twitterCardOg'] && in_array( $name, array(
							'title',
							'description',
							'image',
						) )
					) {
						$is_og    = 'og';
						$name_tag = 'property';
					} else {
						$is_og    = 'twitter';
						$name_tag = 'name';
					}
					$markup .= '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
				}
			}
		} elseif ( is_string( $data ) ) {
			$markup .= '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . PHP_EOL;
		}

		return $markup;
	}
}

