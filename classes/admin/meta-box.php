<?php
namespace TokenToMe\TwitterCards\Admin;
use TokenToMe\TwitterCards\Factory;
use TokenToMe\TwitterCards\Utilities;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Meta_Box{

	/**
	 * Metabox constructor.
	 */
	public function __construct() {
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_metaboxes' ) );
		add_filter( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		$this->opts = \jm_tc_get_options();
	}

	/**
	 * Add some js
	 * for metabox
	 * no need to show all fields if not player
	 */
	public function admin_enqueue_scripts(){

		wp_register_script( 'jm-tc-metabox', JM_TC_URL . 'js/metabox.js', array(), JM_TC_VERSION, true );
		wp_register_style( 'jm-tc-preview', JM_TC_URL . 'css/preview.css', array(), JM_TC_VERSION );

		if ( in_array( get_post_type(), Utilities::get_post_types() ) ) {
			wp_enqueue_script( 'jm-tc-metabox' );
			wp_enqueue_style( 'jm-tc-preview' );
		}
	}

	/**
	 * @return int
	 */
	public static function get_post_id() {
		return isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	}

	/**
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function register_metaboxes( $meta_boxes ) {

		$factory = new Factory();
		$preview = $factory->createPreview( self::get_post_id() );

		$meta_boxes[] = array(
			// Meta box id, UNIQUE per meta box. Optional since 4.1.5
			'id'         => 'jm_tc_metabox',
			// Meta box title - Will appear at the drag and drop handle bar. Required.
			'title'      => __( 'Twitter Cards', 'jm-tc' ),
			// Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
			'post_types' => Utilities::get_post_types(),
			// Where the meta box appear: normal (default), advanced, side. Optional.
			'context'    => 'normal',
			// Order of meta box: high (default), low. Optional.
			'priority'   => 'high',
			// Auto save: true, false (default). Optional.
			'autosave'   => true,
			// List of meta fields
			'fields'     => array(
				array(
					'name'    => __( 'Preview', 'jm-tc' ),
					'id'      => 'twitterCardPreview',
					'type'    => 'custom_html',
					'std'     => $preview->generate_preview(),
				),
				array(
					'name'    => __( 'Card Type', 'jm-tc' ),
					'id'      => 'twitterCardType',
					'type'    => 'select',
					'options' => array(
						'summary'             => __( 'Summary', 'jm-tc' ),
						'summary_large_image' => __( 'Summary below Large Image', 'jm-tc' ),
						'player'              => __( 'Player', 'jm-tc' ),
					),
					'std'     => $this->opts['twitterCardType'],

				),
				array(
					'id'   => 'cardImage',
					'name' => __( 'Set another source as twitter image (enter URL)', 'jm-tc' ),
					'type' => 'file_input',
					'max_file_uploads' => 1,
				),
				array(
					'id'   => 'twitter_featured_size',
					'type' => 'title',
					'name' => __( 'File size' ),
				),
				array(
					'id'   => 'cardPlayer',
					'name' => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ),
					'type' => 'url',
					'tab'  => 'player',

				),
				array(
					'name' => __( 'Player width', 'jm-tc' ),
					'id'   => 'cardPlayerWidth',
					'type' => 'number',
					'desc' => __( 'When setting this, make sure player dimension and image dimensions are exactly the same! Image MUST BE greater than 68,600 pixels (a 262x262 square image, or a 350x196 16:9 image)', 'jm-tc' ),
					'min'  => 262,
					'max'  => 1000,
					'tab'  => 'player',
				),
				array(
					'name' => __( 'Player height', 'jm-tc' ),
					'id'   => 'cardPlayerHeight',
					'type' => 'number',
					'min'  => 196,
					'max'  => 1000,
					'tab'  => 'player',
				),
				array(
					'id'   => 'cardPlayerStream',
					'name' => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ) . '[STREAM]',
					'type' => 'url',
					'desc' => __( 'If you do not understand what is the following field then it is probably a bad idea to fulfill it!', 'jm-tc' ),
					'tab'  => 'player',
				),
				array(
					'id'   => 'cardPlayerCodec',
					'name' => __( 'Codec', 'jm-tc' ),
					'type' => 'text',
					'desc' => __( 'If you do not understand what is the following field then it is probably a bad idea to fulfill it!', 'jm-tc' ),
					'tab'  => 'player',
				),
			),
		);

		return $meta_boxes;

	}
}