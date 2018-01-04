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
		$this->opts    = \jm_tc_get_options();
	}

	/**
	 * @param $type
	 *
	 * @return string
	 */
	public function get_seo_plugin_datas( $type ) {

		$aioseop_title       = get_post_meta( $this->post_ID, '_aioseop_title', true );
		$aioseop_description = get_post_meta( $this->post_ID, '_aioseop_description', true );

		$title = get_the_title( $this->post_ID );
		$desc  = Utilities::get_excerpt_by_id( $this->post_ID );

		if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
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
		$cardType     = ( ! empty( $cardTypePost ) ) ? $cardTypePost : $this->opts['twitterCardType'];

		return array( 'card' => apply_filters( 'jm_tc_card_type', $cardType, $this->post_ID, $this->opts ) );
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

			$cardUsernameKey = ! empty( $this->opts['twitterUsernameKey'] ) ? $this->opts['twitterUsernameKey'] : 'jm_tc_twitter';
			$cardCreator     = get_the_author_meta( $cardUsernameKey, $author_id );

			$cardCreator = ( ! empty( $cardCreator ) ) ? $cardCreator : $this->opts['twitterCreator'];
			$cardCreator = '@' . Utilities::remove_at( $cardCreator );
		}

		return array( 'creator' => apply_filters( 'jm_tc_card_creator', $cardCreator, $this->post_ID, $this->opts ) );
	}

	/**
	 * @return array
	 */
	public function site_username() {

		$cardSite = '@' . Utilities::remove_at( $this->opts['twitterSite'] );

		return array( 'site' => apply_filters( 'jm_tc_card_site', $cardSite, $this->post_ID, $this->opts ) );
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

		return array( 'title' => apply_filters( 'jm_tc_get_title', $cardTitle, $this->post_ID, $this->opts ) );

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

		return array( 'description' => apply_filters( 'jm_tc_get_excerpt', $cardDescription, $this->post_ID, $this->opts ) );

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
			$image            = ! empty( $image_attributes ) && is_array( $image_attributes ) ? reset( $image_attributes ) : $this->opts['twitterImage'];;
		} elseif ( ! empty( $cardImage ) ) {
			$image = esc_url_raw( $cardImage );
		} elseif ( 'attachment' === get_post_type() ) {
			$image = wp_get_attachment_url( $this->post_ID );
		} elseif ( empty( $this->post_ID ) ) {
			$image = $this->opts['twitterImage'];
		}

		return array( 'image' => apply_filters( 'jm_tc_image_source', $image, $this->post_ID, $this->opts ) );

	}

	/**
	 * @link https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
	 * @return array
	 */
	public function image_alt() {

		$cardImageAlt = '';

		if ( $this->post_ID ) {
			$imageAlt     = get_post_meta( $this->post_ID, 'cardImageAlt', true );
			$cardImageAlt = ! empty( $imageAlt ) ? htmlspecialchars( stripcslashes( $imageAlt ) ) : '';
		}

		if ( is_home() || is_front_page() ) {
			$cardImageAlt = $this->opts['twitterImageAlt'];
		}

		$cardImageAlt = Utilities::remove_lb( $cardImageAlt );

		return array( 'image:alt' => apply_filters( 'jm_tc_image_alt', $cardImageAlt, $this->post_ID, $this->opts ) );

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

			$player['player'] = apply_filters( 'jm_tc_player_url', $playerUrl, $this->post_ID, $this->opts );

			//Player
			if ( empty( $player['player'] ) ) {
				return $this->error( __( 'Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', 'jm-tc' ) );
			}

			//Player stream
			if ( ! empty( $playerStreamUrl ) ) {
				$player['player:stream'] = apply_filters( 'jm_tc_player_stream_url', $playerStreamUrl, $this->post_ID, $this->opts );
			}

			$player['player:stream:content_type'] = esc_attr( apply_filters( 'jm_tc_player_codec', 'video/mp4; codecs="avc1.42E01E1, mp4a.40.2"', $this->post_ID, $this->opts ) );

			if ( ! empty( $playerCodec ) ) {
				$player['player:stream:content_type'] = esc_attr( apply_filters( 'jm_tc_player_codec', $playerCodec, $this->post_ID, $this->opts ) );
			}

			//Player width and
			$player['player:width']  = apply_filters( 'jm_tc_player_default_width', 435 );
			$player['player:height'] = apply_filters( 'jm_tc_player_default_height', 251 );
			if ( ! empty( $playerWidth ) && ! empty( $playerHeight ) ) {
				$player['player:width']  = apply_filters( 'jm_tc_player_width', $playerWidth, $this->post_ID, $this->opts );
				$player['player:height'] = apply_filters( 'jm_tc_player_height', $playerHeight, $this->post_ID, $this->opts );
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

		$twitteriPhoneName     = apply_filters( 'jm_tc_iphone_name', $twitteriPhoneName, $this->post_ID, $this->opts );
		$twitteriPadName       = apply_filters( 'jm_tc_ipad_name', $twitteriPadName, $this->post_ID, $this->opts );
		$twitterGooglePlayName = apply_filters( 'jm_tc_googleplay_name', $twitterGooglePlayName, $this->post_ID, $this->opts );
		$twitteriPhoneUrl      = apply_filters( 'jm_tc_iphone_url', $twitteriPhoneUrl, $this->post_ID, $this->opts );
		$twitteriPadUrl        = apply_filters( 'jm_tc_ipad_url', $twitteriPadUrl, $this->post_ID, $this->opts );
		$twitterGooglePlayUrl  = apply_filters( 'jm_tc_googleplay_url', $twitterGooglePlayUrl, $this->post_ID, $this->opts );
		$twitteriPhoneId       = apply_filters( 'jm_tc_iphone_id', $twitteriPhoneId, $this->post_ID, $this->opts );
		$twitteriPadId         = apply_filters( 'jm_tc_ipad_id', $twitteriPadId, $this->post_ID, $this->opts );
		$twitterGooglePlayId   = apply_filters( 'jm_tc_googleplay_id', $twitterGooglePlayId, $this->post_ID, $this->opts );
		$twitterAppCountry     = apply_filters( 'jm_tc_country', $twitterAppCountry, $this->post_ID, $this->opts );

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
