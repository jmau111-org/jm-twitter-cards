<?php

namespace TokenToMe\TwitterCards\Admin;

use TokenToMe\TwitterCards\Thumbs;
use TokenToMe\TwitterCards\Utils as Utilities;

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
	protected $opts = [];
	protected $post_ID;

	public function __construct( $post_ID ) {
		$this->post_ID = $post_ID;
		$this->opts    = \jm_tc_get_options();
	}

	public function get_ID() {
		return $this->post_ID;
	}

	/**
	 * @return array
	 */
	public function card_type() {

		$cardTypePost = get_post_meta( $this->post_ID, 'twitterCardType', true );
		$cardType     = ( ! empty( $cardTypePost ) ) ? $cardTypePost : Utilities::maybe_get_opt( $this->opts, 'twitterCardType' );

		return [ 'card' => apply_filters( 'jm_tc_card_type', $cardType, $this->post_ID, $this->opts ) ];
	}

	/**
	 * @param bool $post_author
	 *
	 * @return array
	 */
	public function creator_username( $post_author = false ) {

		$post_obj    = get_post( $this->post_ID );
		$author_id   = $post_obj->post_author;
		$crea        = ! empty( $this->opts['twitterCreator'] )
			? $this->opts['twitterCreator']
			: '';
		$cardCreator = '@' . Utilities::remove_at( $crea );

		if ( $post_author ) {

			//to be modified or left with the value 'jm_tc_twitter'

			$cardUsernameKey = ! empty( $this->opts['twitterUsernameKey'] ) ? $this->opts['twitterUsernameKey'] : 'jm_tc_twitter';
			$cardCreator     = get_the_author_meta( $cardUsernameKey, $author_id );

			$cardCreator = ( ! empty( $cardCreator ) )
				? $cardCreator
				: $crea;
			$cardCreator = '@' . Utilities::remove_at( $cardCreator );
		}

		return [ 'creator' => apply_filters( 'jm_tc_card_creator', $cardCreator, $this->post_ID, $this->opts ) ];
	}

	/**
	 * @return array
	 */
	public function site_username() {

		$cardSite = '@' . Utilities::remove_at( Utilities::maybe_get_opt( $this->opts, 'twitterSite' ) );

		return [ 'site' => apply_filters( 'jm_tc_card_site', $cardSite, $this->post_ID, $this->opts ) ];
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
				$cardTitle = $this->get_seo_plugin_data( 'title' );
			}

			$cardTitleMeta = get_post_meta( $this->post_ID, 'cardTitle', true );

			if ( ! empty( $cardTitleMeta ) ) {
				$cardTitle = $cardTitleMeta;// allows to override all desc
			}
		}

		return [ 'title' => apply_filters( 'jm_tc_get_title', $cardTitle, $this->post_ID, $this->opts ) ];

	}

	/**
	 * @param $type
	 *
	 * @return string
	 */
	public function get_seo_plugin_data( $type ) {

		$aioseop_title       = get_post_meta( $this->post_ID, '_aioseop_title', true );
		$aioseop_description = get_post_meta( $this->post_ID, '_aioseop_description', true );

		$title = get_the_title( $this->post_ID );
		$desc  = Utilities::get_excerpt_by_id( $this->post_ID );

		if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
			$title = ! empty( $aioseop_title ) ? htmlspecialchars( stripcslashes( $aioseop_title ) ) : the_title_attribute( [ 'echo' => false ] );
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
	public function description() {

		$cardDescription = ! empty( $this->opts['twitterPostPageDesc'] )
			? $this->opts['twitterPostPageDesc']
			: '';
		if ( $this->post_ID ) {

			$cardDescription = Utilities::get_excerpt_by_id( $this->post_ID );

			if ( ! empty( $this->opts['twitterCardDesc'] ) ) {
				$desc            = get_post_meta( $this->post_ID, $this->opts['twitterCardDesc'], true );
				$cardDescription = ! empty( $desc ) ? htmlspecialchars( stripcslashes( $desc ) ) : Utilities::get_excerpt_by_id( $this->post_ID );
			} elseif ( empty( $this->opts['twitterCardDesc'] ) && ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'All_in_One_SEO_Pack' ) ) ) {
				$cardDescription = $this->get_seo_plugin_data( 'desc' );
			}
		}

		$cardDesc = get_post_meta( $this->post_ID, 'cardDesc', true );

		if ( ! empty( $cardDesc ) ) {
			$cardDescription = $cardDesc;// allows to override all desc
		}

		$cardDescription = Utilities::remove_lb( $cardDescription );

		return [ 'description' => apply_filters( 'jm_tc_get_excerpt', $cardDescription, $this->post_ID, $this->opts ) ];

	}
	
	/**
	 * @return array|bool
	 */
	public function image() {

		$cardImage   = get_post_meta( $this->post_ID, 'cardImage', true );
		$cardImageID = get_post_meta( $this->post_ID, 'cardImageID', true );

		if ( ! empty( $cardImageID ) ) {
			$cardImage = wp_get_attachment_image_url( $cardImageID, Utilities::maybe_get_opt( $this->opts, 'twitterImage' ) );
		}

		// fallback
		$image = Utilities::maybe_get_opt( $this->opts, 'twitterImage' );

		if ( $this->post_ID && empty( $cardImage ) && has_post_thumbnail( $this->post_ID ) ) {
			$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post_ID ), 'full' );
			$image            = ! empty( $image_attributes ) && is_array( $image_attributes ) ? reset( $image_attributes ) : $image;
		} elseif ( ! empty( $cardImage ) ) {
			$image = esc_url_raw( $cardImage );
		} elseif ( 'attachment' === get_post_type() ) {
			$image = wp_get_attachment_url( $this->post_ID );
		} elseif ( empty( $this->post_ID ) ) {
			$image = Utilities::maybe_get_opt( $this->opts, 'twitterImage' );
		}

		return [ 'image' => apply_filters( 'jm_tc_image_source', $image, $this->post_ID, $this->opts ) ];

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
			$cardImageAlt = Utilities::maybe_get_opt( $this->opts, 'twitterImageAlt' );
		}

		$cardImageAlt = Utilities::remove_lb( $cardImageAlt );

		return [ 'image:alt' => apply_filters( 'jm_tc_image_alt', $cardImageAlt, $this->post_ID, $this->opts ) ];

	}

	/**
	 * @return array|bool
	 */
	public function player() {

		$cardType = apply_filters( 'jm_tc_card_type', get_post_meta( $this->post_ID, 'twitterCardType', true ) );

		if ( 'player' === $cardType ) {

			$playerUrl       = get_post_meta( $this->post_ID, 'cardPlayer', true );
			$playerWidth     = get_post_meta( $this->post_ID, 'cardPlayerWidth', true );
			$playerHeight    = get_post_meta( $this->post_ID, 'cardPlayerHeight', true );
			$player          = [];

			$player['player'] = apply_filters( 'jm_tc_player_url', $playerUrl, $this->post_ID, $this->opts );

			//Player
			if ( empty( $player['player'] ) ) {
				return $this->error( __( 'Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', 'jm-tc' ) );
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

	/**
	 * @return array
	 */
	public function deep_linking() {

		$app = [
			'app:name:iphone'     => apply_filters( 'jm_tc_iphone_name', Utilities::maybe_get_opt( $this->opts, 'twitteriPhoneName' ), $this->post_ID, $this->opts ),
			'app:name:ipad'       => apply_filters( 'jm_tc_ipad_name', Utilities::maybe_get_opt( $this->opts, 'twitteriPadName' ), $this->post_ID, $this->opts ),
			'app:name:googleplay' => apply_filters( 'jm_tc_googleplay_name', Utilities::maybe_get_opt( $this->opts, 'twitterGooglePlayName' ), $this->post_ID, $this->opts ),
			'app:url:iphone'      => apply_filters( 'jm_tc_iphone_url', Utilities::maybe_get_opt( $this->opts, 'twitteriPhoneUrl' ), $this->post_ID, $this->opts ),
			'app:url:ipad'        => apply_filters( 'jm_tc_ipad_url', Utilities::maybe_get_opt( $this->opts, 'twitteriPadUrl' ), $this->post_ID, $this->opts ),
			'app:url:googleplay'  => apply_filters( 'jm_tc_googleplay_url', Utilities::maybe_get_opt( $this->opts, 'twitterGooglePlayUrl' ), $this->post_ID, $this->opts ),
			'app:id:iphone'       => apply_filters( 'jm_tc_iphone_id', Utilities::maybe_get_opt( $this->opts, 'twitteriPhoneId' ), $this->post_ID, $this->opts ),
			'app:id:ipad'         => apply_filters( 'jm_tc_ipad_id', Utilities::maybe_get_opt( $this->opts, 'twitteriPadId' ), $this->post_ID, $this->opts ),
			'app:id:googleplay'   => apply_filters( 'jm_tc_googleplay_id', Utilities::maybe_get_opt( $this->opts, 'twitterGooglePlayId' ), $this->post_ID, $this->opts ),
			'app:id:country'      => apply_filters( 'jm_tc_country', Utilities::maybe_get_opt( $this->opts, 'twitterAppCountry' ), $this->post_ID, $this->opts ),
		];

		return $return = array_map( 'esc_attr', $app );

	}

}