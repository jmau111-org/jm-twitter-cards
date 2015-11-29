<?php
namespace TokenToMe\TwitterCards\Admin;
use TokenToMe\TwitterCards\Utilities;
use TokenToMe\TwitterCards\Thumbs;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Options {

	/**
	 * options
	 *
	 * @var array
	 */
	protected $opts = array();
	protected $post_ID;

	public function __construct( $post_ID ) {
		$this->post_ID = $post_ID;
		$this->opts = \jm_tc_get_options();
	}

	/**
	 * @param $type
	 *
	 * @return string|void
	 */
	public function get_seo_plugin_datas( $type ) {

		$aioseop_title           = get_post_meta( $this->post_ID, '_aioseop_title', true );
		$aioseop_description     = get_post_meta( $this->post_ID, '_aioseop_description', true );
		$yoast_wpseo_title       = get_post_meta( $this->post_ID, '_yoast_wpseo_title', true );
		$yoast_wpseo_description = get_post_meta( $this->post_ID, '_yoast_wpseo_metadesc', true );

		$title = get_the_title( $this->post_ID );
		$desc  = Utilities::get_excerpt_by_id( $this->post_ID );

		if ( class_exists( 'WPSEO_Frontend' ) ) {
			$title = ! empty( $yoast_wpseo_title ) ? htmlspecialchars( stripcslashes( $yoast_wpseo_title ) ) : get_the_title( $this->post_ID );
			$desc  = ! empty( $yoast_wpseo_description ) ? htmlspecialchars( stripcslashes( $yoast_wpseo_description ) ) : Utilities::get_excerpt_by_id( $this->post_ID );

		} elseif ( class_exists( 'All_in_One_SEO_Pack' ) ) {
			$title = ! empty( $aioseop_title ) ? htmlspecialchars( stripcslashes( $aioseop_title ) ) : the_title_attribute( array( 'echo' => false ) );
			$desc  = ! empty( $aioseop_description ) ? htmlspecialchars( stripcslashes( $aioseop_description ) ) : Utilities::get_excerpt_by_id( $this->post_ID );
		}

		switch ( $type ) {
			case 'title' :
				return $title;
				break;
			case 'desc' :
				return $desc;
				break;
			default:
				return $title;
		}
	}


	/**
	 * @return array
	 */
	public function card_type() {

		$cardTypePost = get_post_meta( $this->post_ID, 'twitterCardType', true );
		$cardType = ( ! empty( $cardTypePost ) ) ? $cardTypePost : $this->opts['twitterCardType'];

		return array( 'card' => apply_filters( 'jm_tc_card_type', $cardType ) );
	}

	/**
	 * @param bool $post_author
	 *
	 * @return array
	 */
	public function creator_username( $post_author = false ) {

		$post_obj    = get_post( $this->post_ID );
		$author_id   = $post_obj->post_author;
		$cardCreator = '@' . Utilities::remove_at( $this->opts['twitterCreator'] );

		if ( $post_author ) {

			//to be modified or left with the value 'jm_tc_twitter'

			$cardUsernameKey = $this->opts['twitterUsernameKey'];
			$cardCreator     = get_the_author_meta( $cardUsernameKey, $author_id );

			$cardCreator = ( ! empty( $cardCreator ) ) ? $cardCreator : $this->opts['twitterCreator'];
			$cardCreator = '@' . Utilities::remove_at( $cardCreator );
		}

		return array( 'creator' => apply_filters( 'jm_tc_card_creator', $cardCreator ) );
	}

	/**
	 * @return array
	 */
	public function site_username() {

		$cardSite = '@' . Utilities::remove_at( $this->opts['twitterSite'] );

		return array( 'site' => apply_filters( 'jm_tc_card_site', $cardSite ) );
	}


	/**
	 * @return array
	 */
	public function title() {

		$cardTitle = get_bloginfo( 'name' );

		if ( $this->post_ID ) {

			$cardTitle = get_the_title( $this->post_ID );

			if ( ! empty( $this->opts['twitterCardTitle'] ) ) {
				$title     = get_post_meta( $this->post_ID, $this->opts['twitterCardTitle'], true ); // this one is pretty hard to debug ^^
				$cardTitle = ! empty( $title ) ? htmlspecialchars( stripcslashes( $title ) ) : get_the_title( $this->post_ID );

			} elseif ( empty( $this->opts['twitterCardTitle'] ) && ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'All_in_One_SEO_Pack' ) ) ) {
				$cardTitle = $this->get_seo_plugin_datas( 'title' );
			}
		}
		return array( 'title' => apply_filters( 'jm_tc_get_title', $cardTitle ) );

	}

	/**
	 * @return array
	 */
	public function description() {

		$cardDescription = $this->opts['twitterPostPageDesc'];
		if ( $this->post_ID ) {

			$cardDescription = Utilities::get_excerpt_by_id( $this->post_ID );

			if ( ! empty( $this->opts['twitterCardDesc'] ) ) {
				$desc            = get_post_meta( $this->post_ID, $this->opts['twitterCardDesc'], true );
				$cardDescription = ! empty( $desc ) ? htmlspecialchars( stripcslashes( $desc ) ) : Utilities::get_excerpt_by_id( $this->post_ID );
			} elseif ( empty( $this->opts['twitterCardDesc'] ) && ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'All_in_One_SEO_Pack' ) ) ) {
				$cardDescription = $this->get_seo_plugin_datas( 'desc' );
			}
		}
		$cardDescription = Utilities::remove_lb( $cardDescription );

		return array( 'description' => apply_filters( 'jm_tc_get_excerpt', $cardDescription ) );

	}


	/**
	 * @return array|bool
	 */
	public function image() {

		$cardImage = get_post_meta( $this->post_ID, 'cardImage', true );

		//fallback
		$image = $this->opts['twitterImage'];

		if ( $this->post_ID && empty( $cardImage ) && has_post_thumbnail( $this->post_ID ) ) {
			$size             = Thumbs::thumbnail_sizes();
			$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post_ID ), $size );
			$image            = reset($image_attributes);
		} elseif ( ! empty( $cardImage ) ) {
			$image = wp_get_attachment_url( (int) $cardImage );
		} elseif ( 'attachment' === get_post_type() ) {
			$image = wp_get_attachment_url( $this->post_ID );
		} elseif ( empty( $this->post_ID ) ) {
			$image = $this->opts['twitterImage'];
		}

		return array( 'image' => apply_filters( 'jm_tc_image_source', $image ) );

	}

	/**
	 * @return array|bool
	 */
	public function player() {

		$cardType = apply_filters( 'jm_tc_card_type', get_post_meta( $this->post_ID, 'twitterCardType', true ) );

		if ( 'player' === $cardType ) {

			$playerUrl       = get_post_meta( $this->post_ID, 'cardPlayer', true );
			$playerStreamUrl = get_post_meta( $this->post_ID, 'cardPlayerStream', true );
			$playerWidth     = get_post_meta( $this->post_ID, 'cardPlayerWidth', true );
			$playerHeight    = get_post_meta( $this->post_ID, 'cardPlayerHeight', true );
			$playerCodec     = get_post_meta( $this->post_ID, 'cardPlayerCodec', true );
			$player          = array();

			//Player
			if ( empty( $playerUrl ) ) {
				return $this->error( __( 'Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', 'jm-tc' ) );
			}

			$player['player'] = apply_filters( 'jm_tc_player_url', $playerUrl );

			//Player stream
			if ( ! empty( $playerStreamUrl ) ) {
				$player['player:stream'] = apply_filters( 'jm_tc_player_stream_url', $playerStreamUrl );
			}

			$player['player:stream:content_type'] = esc_attr( apply_filters( 'jm_tc_player_codec', 'video/mp4; codecs="avc1.42E01E1, mp4a.40.2"' ) );

			if ( ! empty( $playerCodec ) ) {
				$player['player:stream:content_type'] = esc_attr( apply_filters( 'jm_tc_player_codec', $playerCodec ) );
			}

			//Player width and
			$player['player:width']  = apply_filters( 'jm_tc_player_default_width', 435 );
			$player['player:height'] = apply_filters( 'jm_tc_player_default_height', 251 );
			if ( ! empty( $playerWidth ) && ! empty( $playerHeight ) ) {
				$player['player:width']  = apply_filters( 'jm_tc_player_width', $playerWidth );
				$player['player:height'] = apply_filters( 'jm_tc_player_height', $playerHeight );
			}

			return $player;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function deep_linking() {

		$twitteriPhoneName     = ( ! empty( $this->opts['twitteriPhoneName'] ) ) ? $this->opts['twitteriPhoneName'] : '';
		$twitteriPadName       = ( ! empty( $this->opts['twitteriPadName'] ) ) ? $this->opts['twitteriPadName'] : '';
		$twitterGooglePlayName = ( ! empty( $this->opts['twitterGooglePlayName'] ) ) ? $this->opts['twitterGooglePlayName'] : '';
		$twitteriPhoneUrl      = ( ! empty( $this->opts['twitteriPhoneUrl'] ) ) ? $this->opts['twitteriPhoneUrl'] : '';
		$twitteriPadUrl        = ( ! empty( $this->opts['twitteriPadUrl'] ) ) ? $this->opts['twitteriPadUrl'] : '';
		$twitterGooglePlayUrl  = ( ! empty( $this->opts['twitterGooglePlayUrl'] ) ) ? $this->opts['twitterGooglePlayUrl'] : '';
		$twitteriPhoneId       = ( ! empty( $this->opts['twitteriPhoneId'] ) ) ? $this->opts['twitteriPhoneId'] : '';
		$twitteriPadId         = ( ! empty( $this->opts['twitteriPadId'] ) ) ? $this->opts['twitteriPadId'] : '';
		$twitterGooglePlayId   = ( ! empty( $this->opts['twitterGooglePlayId'] ) ) ? $this->opts['twitterGooglePlayId'] : '';
		$twitterAppCountry     = ( ! empty( $this->opts['twitterAppCountry'] ) ) ? $this->opts['twitterAppCountry'] : '';

		$twitteriPhoneName     = apply_filters( 'jm_tc_iphone_name', $twitteriPhoneName );
		$twitteriPadName       = apply_filters( 'jm_tc_ipad_name', $twitteriPadName );
		$twitterGooglePlayName = apply_filters( 'jm_tc_googleplay_name', $twitterGooglePlayName );
		$twitteriPhoneUrl      = apply_filters( 'jm_tc_iphone_url', $twitteriPhoneUrl );
		$twitteriPadUrl        = apply_filters( 'jm_tc_ipad_url', $twitteriPadUrl );
		$twitterGooglePlayUrl  = apply_filters( 'jm_tc_googleplay_url', $twitterGooglePlayUrl );
		$twitteriPhoneId       = apply_filters( 'jm_tc_iphone_id', $twitteriPhoneId );
		$twitteriPadId         = apply_filters( 'jm_tc_ipad_id', $twitteriPadId );
		$twitterGooglePlayId   = apply_filters( 'jm_tc_googleplay_id', $twitterGooglePlayId );
		$twitterAppCountry     = apply_filters( 'jm_tc_country', $twitterAppCountry );

		$app = array(
			'app:name:iphone'     => $twitteriPhoneName,
			'app:name:ipad'       => $twitteriPadName,
			'app:name:googleplay' => $twitterGooglePlayName,
			'app:url:iphone'      => $twitteriPhoneUrl,
			'app:url:ipad'        => $twitteriPadUrl,
			'app:url:googleplay'  => $twitterGooglePlayUrl,
			'app:id:iphone'       => $twitteriPhoneId,
			'app:id:ipad'         => $twitteriPadId,
			'app:id:googleplay'   => $twitterGooglePlayId,
			'app:id:country'      => $twitterAppCountry,
		);

		return $return = array_map( 'esc_attr', $app );

	}


	/**
	 * @param bool $error
	 *
	 * @return bool
	 */
	protected function error( $error = false ) {

		if ( $error && current_user_can( 'edit_posts' ) ) {
			return $error;
		}

		return false;

	}


}