<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Factory;
use TokenToMe\TwitterCards\Utilities;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Metabox {

	protected $keys;
	protected $opts;

	function __construct() {

		$this->keys = [
			'twitterCardType',
			'cardImage',
			'cardImageAlt',
			'cardPlayer',
			'cardPlayerWidth',
			'cardPlayerHeight',
			'cardPlayerStream',
			'cardPlayerCodec',
		];

		add_action( 'add_meta_boxes', [ $this, 'add_box' ], 10, 2 );
		add_action( 'save_post', [ $this, 'save_box' ], 10, 3 );

		add_filter( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		$this->opts = \jm_tc_get_options();
	}

	/**
	 * register box
	 *
	 * @param $post_type
	 * @param $post
	 *
	 * @author Julien Maury
	 * @return mixed|void
	 */
	public function add_box( $post_type, $post ) {
		add_meta_box(
			'jm_tc_metabox',
			__( 'Twitter Cards', 'jm-tc' ),
			[ $this, 'display_box' ],
			Utilities::get_post_types()
		);
	}

	/**
	 * callback - box
	 *
	 * @author Julien Maury
	 */
	public function display_box() {

		$factory = new Factory();
		$factory->generatePreview( self::get_post_id() );

		$metaBox = [
			[ 'method' => 'wrapper', 'tag' => 'table', 'class' => 'form-table', 'mod' => 'start' ],
			[ 'method' => 'wrapper', 'tag' => 'tbody', 'mod' => 'start' ],
			[
				'method'   => 'select_field',
				'label'    => __( 'Card type', 'jm-tc' ),
				'field_id' => 'twitterCardType',
				'options'  => [
					'summary'             => __( 'Summary', 'jm-tc' ),
					'summary_large_image' => __( 'Summary below Large Image', 'jm-tc' ),
					'player'              => __( 'Player', 'jm-tc' ),
					'app'                 => __( 'Application', 'jm-tc' ),
				],
				'type'     => 'select_field',
				'value'    => get_post_meta( self::get_post_id(), 'twitterCardType', true ) ? get_post_meta( self::get_post_id(), 'twitterCardType', true ) : $this->opts['twitterCardType'],
			],
			[
				'method'   => 'image_field',
				'field_id' => 'cardImage',
				'label'    => __( 'Set another source as twitter image (enter URL)', 'jm-tc' ),
				'value'    => get_post_meta( self::get_post_id(), 'cardImage', true ),
			],
			[
				'method'    => 'textarea_field',
				'field_id'  => 'cardImageAlt',
				'label'     => __( 'Image Alt', 'jm-tc' ),
				'value'     => get_post_meta( self::get_post_id(), 'cardImageAlt', true ),
				'charcount' => 420,
			],
			[
				'method'   => 'url_field',
				'field_id' => 'cardPlayer',
				'label'    => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ),
				'value'    => get_post_meta( self::get_post_id(), 'cardPlayer', true ),
			],
			[
				'method'   => 'num_field',
				'field_id' => 'cardPlayerWidth',
				'label'    => __( 'Player width', 'jm-tc' ),
				'min'      => 262,
				'max'      => 1000,
				'step'     => 1,
				'value'    => get_post_meta( self::get_post_id(), 'cardPlayerWidth', true ),
			],
			[
				'method'   => 'num_field',
				'field_id' => 'cardPlayerHeight',
				'label'    => __( 'Player height', 'jm-tc' ),
				'type'     => 'number',
				'min'      => 196,
				'max'      => 1000,
				'step'     => 1,
				'value'    => get_post_meta( self::get_post_id(), 'cardPlayerHeight', true ),
			],
			[
				'method'   => 'url_field',
				'field_id' => 'cardPlayerStream',
				'label'    => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ),
				'value'    => get_post_meta( self::get_post_id(), 'cardPlayerStream', true ),
			],
			[
				'method'   => 'text_field',
				'field_id' => 'cardPlayerCodec',
				'label'    => __( 'Codec', 'jm-tc' ),
				'value'    => get_post_meta( self::get_post_id(), 'cardPlayerCodec', true ),
			],
			[ 'method' => 'wrapper', 'tag' => 'tbody', 'mod' => 'end' ],
			[ 'method' => 'wrapper', 'tag' => 'table', 'mod' => 'end' ],
		];

		$factory->generateMetaBox( $metaBox );
		wp_nonce_field( 'save_tc_meta', 'save_tc_meta_nonce' );

	}

	/**
	 * @author Julien Maury
	 * @return int
	 */
	public static function get_post_id() {
		return isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	}

	/**
	 * The save part
	 *
	 * @param int $post_id The post ID.
	 * @param \WP_Post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 *
	 * @author Julien Maury
	 *
	 * @return int
	 */
	public function save_box( $post_id, $post, $update ) {

		$types = Utilities::get_post_types();

		if ( ! in_array( $post->post_type, $types ) ) {
			return $post_id;
		}

		if ( ! empty( $_POST['save_tc_meta_nonce'] ) && check_admin_referer( 'save_tc_meta', 'save_tc_meta_nonce' ) ) {
			foreach ( (array) $this->keys as $key ) {

				if ( ! empty( $_POST[ $key ] ) ) {
					update_post_meta( $post_id, $key, $this->sanitize( $key, $_POST[ $key ] ) );
				} elseif ( empty( $_POST[ $key ] ) ) {
					delete_post_meta( $post_id, $key );
				}
			}

		}

		return $post_id;

	}

	/**
	 * Validate values
	 *
	 * @param $key
	 * @param $value
	 *
	 * @author Julien Maury
	 * @return int|bool|string
	 */
	public function sanitize( $key, $value ) {

		switch ( $key ) {
			case 'twitterCardType':
				return in_array( $value, [
					'summary',
					'summary_large_image',
					'player',
					'app',
				] ) ? sanitize_text_field( $value ) : $this->opts['twitterCardType'];
				break;
			case 'cardImageAlt':
				return esc_textarea( $value );
				break;
			case 'cardImage':
			case 'cardPlayer':
			case 'cardPlayerStream':
				return esc_url_raw( $value );
				break;
			case 'cardPlayerWidth':
			case 'cardPlayerHeight':
				return intval( $value );
				break;
			case 'cardPlayerCodec':
				return sanitize_text_field( $value );
				break;
			default:
				return false;
		}

	}

	/**
	 * Add some js
	 * for metabox
	 * no need to show all fields if not player
	 *
	 * @author Julien Maury
	 */
	public function admin_enqueue_scripts() {

		wp_register_script( 'jm-tc-metabox', JM_TC_URL . 'js/metabox.js', [ 'jquery' ], JM_TC_VERSION, true );
		wp_register_style( 'jm-tc-preview', JM_TC_URL . 'css/preview.css', [], JM_TC_VERSION );

		if ( in_array( get_post_type(), Utilities::get_post_types() ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'count-chars' );
			wp_enqueue_script( 'jm-tc-metabox' );

			wp_localize_script(
				'jm-tc-metabox',
				'tcStrings',
				[
					'upload_message' => __( 'Upload' ),
					'default_image'  => ! empty( $this->opts['twitterImage'] ) ? esc_url( $this->opts['twitterImage'] ) : JM_TC_URL . 'assets/img/Twitter_logo_blue.png',
				]
			);

			wp_enqueue_style( 'jm-tc-preview' );

		}
	}
}
