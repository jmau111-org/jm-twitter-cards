<?php
namespace TokenToMe\TwitterCards\Admin;
use TokenToMe\TwitterCards\Utilities;

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
	protected $opts = array();
	protected $options = array();

	public function __construct( Options $options ) {

		$this->options = $options;
		$this->opts    = \jm_tc_get_options();
	}

	public function __toString(){
		$preview = '';
		ob_start();
		require_once ( JM_TC_DIR . 'views/preview.php' );
		ob_end_flush();
		return $preview;
	}

}