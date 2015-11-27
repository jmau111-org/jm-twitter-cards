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
	protected $opts    = array();
	protected $options = array();

	public function __construct( Admin\Options $options ) {

		$this->options = $options;
		$this->opts = \jm_tc_get_options();
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
	 * @param bool|true $echo
	 *
	 * @return string
	 */
	public function generate_markup( $echo = true ) {

		$markup  = $this->html_comments();

		/* most important meta */
		$markup .= $this->display( $this->options->card_type() );
		$markup .= $this->display( $this->options->creator_username( true ) );
		$markup .= $this->display( $this->options->site_username() );
		$markup .= $this->display( $this->options->title() );
		$markup .= $this->display( $this->options->description() );
		$markup .= $this->display( $this->options->image() );

		/* secondary meta */
		$markup .= $this->display( $this->options->player() );
		$markup .= $this->display( $this->options->deep_linking() );

		$markup .= $this->html_comments( true );

		if ( $echo ) {
			echo $markup;
		} else {
			return $markup;
		}
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function display( $data) {

		$markup = '';

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
			$markup .=  '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . PHP_EOL;
		}
		return $markup;
	}
}

