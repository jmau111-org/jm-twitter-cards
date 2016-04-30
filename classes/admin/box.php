<?php
namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Factory;
use TokenToMe\TwitterCards\Utilities;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Box implements MetaBox {

	protected $keys;
	protected $opts;

	function __construct() {

		$this->keys = array(
			'twitterCardType',
			'cardImage',
			'cardPlayer',
			'cardPlayerWidth',
			'cardPlayerHeight',
			'cardPlayerStream',
			'cardPlayerCodec'
		);

		add_action( 'add_meta_boxes', array( $this, 'add_box' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_box' ), 10, 3 );

		add_filter( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
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
			array( $this, 'display_box' ),
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

		$factory->createPreview( self::get_post_id() );

		echo $factory->createFields()->wrapper( array( 'tag' => 'table', 'class' => 'form-table', ) );
		echo $factory->createFields()->wrapper( array( 'tag' => 'tbody', ) );
		echo $factory->createFields()->select_field(
			array(
				'label'    => __( 'Card type', 'jm-tc' ),
				'field_id' => 'twitterCardType',
				'options'  => array(
					'summary'             => __( 'Summary', 'jm-tc' ),
					'summary_large_image' => __( 'Summary below Large Image', 'jm-tc' ),
					'player'              => __( 'Player', 'jm-tc' ),
				),
				'value'    => get_post_meta( self::get_post_id(), 'twitterCardType', true ),
			)
		);
		echo $factory->createFields()->image_field( array(
			'field_id' => 'cardImage',
			'label'    => __( 'Set another source as twitter image (enter URL)', 'jm-tc' ),
			'value'    => get_post_meta( self::get_post_id(), 'cardImage', true ),
		) );
		echo $factory->createFields()->url_field( array(
			'field_id' => 'cardPlayer',
			'label'    => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ),
			'value'    => get_post_meta( self::get_post_id(), 'cardPlayer', true ),
		) );
		echo $factory->createFields()->num_field( array(
			'field_id' => 'cardPlayerWidth',
			'label'    => __( 'Player width', 'jm-tc' ),
			'min'      => 262,
			'max'      => 1000,
			'step'     => 1,
			'value'    => get_post_meta( self::get_post_id(), 'cardPlayerWidth', true ),
		) );
		echo $factory->createFields()->num_field( array(
			'field_id' => 'cardPlayerHeight',
			'label'    => __( 'Player height', 'jm-tc' ),
			'type'     => 'number',
			'min'      => 196,
			'max'      => 1000,
			'step'     => 1,
			'value'    => get_post_meta( self::get_post_id(), 'cardPlayerHeight', true ),
		) );
		echo $factory->createFields()->url_field( array(
			'field_id' => 'cardPlayerStream',
			'label'    => __( 'URL of iFrame player (MUST BE HTTPS)', 'jm-tc' ),
			'value'    => get_post_meta( self::get_post_id(), 'cardPlayerStream', true ),
		) );
		echo $factory->createFields()->text_field( array(
			'field_id' => 'cardPlayerCodec',
			'label'    => __( 'Codec', 'jm-tc' ),
			'value'    => get_post_meta( self::get_post_id(), 'cardPlayerCodec', true ),
		) );

		wp_nonce_field( 'save_tc_meta', 'save_tc_meta_nonce' );

		echo $factory->createFields()->wrapper( array( 'tag' => 'tbody', ), 'end' );
		echo $factory->createFields()->wrapper( array( 'tag' => 'table', ), 'end' );

	}

	/**
	 * The save part
	 *
	 * @param int      $post_id The post ID.
	 * @param \WP_Post $post The post object.
	 * @param bool     $update Whether this is an existing post being updated or not.
	 *
	 * @author Julien Maury
	 *
	 * @return int|void
	 */
	public function save_box( $post_id, $post, $update ) {

		$types = Utilities::get_post_types();

		if ( ! in_array( $post->post_type, $types ) ) {
			return $post_id;
		}

		if ( ! empty( $_POST ) && check_admin_referer( 'save_tc_meta', 'save_tc_meta_nonce' ) ) {

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
				return in_array( $value, array(
					'summary',
					'summary_large_image',
					'player'
				) ) ? sanitize_text_field( $value ) : $this->opts['twitterCardType'];
				break;
			case 'cardImage':
			case 'cardPlayer':
			case 'cardPlayerStream':
				return esc_url_raw( $value );
				break;
			case 'cardPlayerWidth':
			case 'cardPlayerHeight':
				return (int) $value;
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

		wp_register_script( 'jm-tc-metabox', JM_TC_URL . 'js/metabox.js', array( 'jquery' ), JM_TC_VERSION, true );
		wp_register_script( 'jm-tc-preview', JM_TC_URL . 'js/preview.js', array('jquery'), JM_TC_VERSION, true );
		wp_register_style( 'jm-tc-preview', JM_TC_URL . 'css/preview.css', array(), JM_TC_VERSION );

		if ( in_array( get_post_type(), Utilities::get_post_types() ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'jm-tc-metabox' );
			wp_enqueue_script( 'jm-tc-preview' );

			wp_localize_script(
				'jm-tc-metabox',
				'tcStrings',
				array(
					'upload_message' => __( 'Upload'),
					'logo_twitter'   => JM_TC_URL . 'assets/img/Twitter_logo_blue.png',
				)
			);

			wp_enqueue_style( 'jm-tc-preview' );

		}
	}

	/**
	 * @author Julien Maury
	 * @return int
	 */
	public static function get_post_id() {
		return isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	}
}