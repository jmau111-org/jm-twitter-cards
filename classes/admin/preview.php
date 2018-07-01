<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Preview {

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $opts = [];
	protected $options = [];

	public function __construct( Options $options ) {

		$this->options = $options;
		$this->opts    = \jm_tc_get_options();
	}

	public function __toString() {
		$preview = '';
		$type    = $this->options->card_type();
		$title   = $this->options->title();
		$desc    = $this->options->description();
		$image   = $this->options->image();

		if ( ! is_array( $type ) || ! is_array( $title ) || ! is_array( $desc ) || ! is_array( $image ) ) {
			return false;
		}

		$type   = reset( $type );
		$title  = reset( $title );
		$desc   = reset( $desc );
		$image  = reset( $image );
		$domain = $_SERVER['HTTP_HOST'];
		$avatar = get_avatar_url( get_avatar( get_current_user_id(), 36, 36 ) );
		$style  = 'summary' === $type ? 'style="background-image:url( ' . esc_url( $image ) . ');"' : '';


		ob_start();
		require_once( JM_TC_DIR . 'views/preview.php' );
		ob_end_clean();

		return $preview;
	}

}