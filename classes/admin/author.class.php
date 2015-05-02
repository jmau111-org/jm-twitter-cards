<?php
namespace TokenToMe\twitter_cards;

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Author {

	/**
	 * Create suggested plugins list
	 * @since  5.3.0
	 * @return string
	 *
	 * @param array $slugs
	 */
	static function get_plugins_list( $slugs = array() ) {

		$list = '';

		foreach ( $slugs as $slug => $name ) {

			$list .= '<a class="button" target="_blank" href="http://wordpress.org/plugins/' . $slug . '">' . __( $name, 'jm-tc' ) . '</a> ';
		}

		return $list;

	}

	/**
	 * Displays author infos
	 * @since  5.3.0
	 * @return string
	 *
	 * @param $name
	 * @param $desc
	 * @param $gravatar_email
	 * @param $url
	 * @param $donation
	 * @param $twitter
	 * @param $googleplus
	 * @param array $slugs
	 */
	static function get_author_infos( $name, $desc, $gravatar_email, $url, $donation, $twitter, $googleplus, $slugs = array() ) {

		$output  = '<h3 class="hndle">' . __( 'The developer', JM_TC_TEXTDOMAIN ) . '</h3>';
		$output .= '<img src="' . esc_url( 'http://www.gravatar.com/avatar/' . md5( $gravatar_email ) ) . '" alt=""/>';
		$output .= esc_html( $name );
		$output .= wpautop( $desc );
		$output .= '<a class="social button button-secondary dashicons-before dashicons-admin-site" href="' . esc_url( $url ) . '" target="_blank" title="' . esc_attr__( 'My website', 'jm-tc' ) . '"><span class="visually-hidden">' . __( 'My website', JM_TC_TEXTDOMAIN ) . '</span></a>';
		$output .= '<a class="social button button-secondary link-like dashicons-before dashicons-twitter" href="http://twitter.com/intent/user?screen_name=' . Utilities::remove_at( $twitter ) . '" title="' . esc_attr__( 'Follow me', JM_TC_TEXTDOMAIN ) . '"> <span class="visually-hidden">' . __( 'Follow me', JM_TC_TEXTDOMAIN ) . '</span></a>';
		$output .= '<a class="social button button-secondary dashicons-before dashicons-googleplus" href="' . esc_url( $googleplus ) . '" target="_blank" title="' . esc_attr__( 'Add me to your circles', JM_TC_TEXTDOMAIN ) . '"> <span class="visually-hidden">' . __( 'Add me to your circles', JM_TC_TEXTDOMAIN ) . '</span></a>';
		$output .= '<h3><span>' . __( 'Keep the plugin free', JM_TC_TEXTDOMAIN ) . '</span></h3>';
		$output .= wpautop( __( 'Please help if you want to keep this plugin free.', JM_TC_TEXTDOMAIN ) );
		$output .= '
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="' . esc_attr( $donation ) . '">
						<input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
						</form>
						';
		$output .= '<h3><span>' . __( 'Plugin', JM_TC_TEXTDOMAIN ) . '</span></h3>';
		$output .= wpautop( __( 'Maybe you will like this plugin too: ', JM_TC_TEXTDOMAIN ) . self::get_plugins_list( $slugs ) );

		echo $output;
	}
}
