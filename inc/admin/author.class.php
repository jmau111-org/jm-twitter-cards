<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Author') ) {

	class JM_TC_Author{

		public static function get_plugins_list( $slugs = array() ) {
			
			$list = '<ul class="plugins-list">';

			foreach ( $slugs as $slug => $name ){
				
				$list .= '<li><a class="button" target="_blank" href="http://wordpress.org/plugins/'.$slug.'">'.__( $name, 'jm-tc').'</a></li>';
			}
			
			$list .= '</ul>';
			return $list;
			
		}
		
		public static function get_author_infos($name, $desc, $gravatar_email, $url, $donation, $twitter, $googleplus, $slugs = array() ) {

			$infos   = '<div class="inbl">';
			$infos  .= '<h3 class="hndle">'.__('The developer', 'jm-tc').'</h3>';
			$infos  .= '<figure>';
			$infos  .= '<img class="totheleft" src="http://www.gravatar.com/avatar/'.md5($gravatar_email).'" alt=""/>';
			$infos  .= '<figcaption class="totheright">';
			$infos  .= $name;
			$infos  .= '<p>'.$desc.'</p>';
			$infos  .= '<ul class="social-links">';
			$infos  .= '<li class="inbl"><a class="social button button-secondary dashicons-before dashicons-admin-site" href="'.$url.'" target="_blank" title="'.esc_attr__('My website', 'jm-tc').'"><span class="visually-hidden">'.__('My website', 'jm-tc').'</span></a></li>';
			$infos  .= '<li class="inbl"><a class="social button button-secondary link-like dashicons-before dashicons-twitter" href="http://twitter.com/intent/user?screen_name='.$twitter.'" title="'.esc_attr__('Follow me', 'jm-tc').'"> <span class="visually-hidden">'.__('Follow me', 'jm-tc').'</span></a></li>';
			$infos  .= '<li class="inbl"><a class="social button button-secondary dashicons-before dashicons-googleplus" href="'.$googleplus.'" target="_blank" title="'.esc_attr__('Add me to your circles', 'jm-tc').'"> <span class="visually-hidden">'.__('Add me to your circles', 'jm-tc').'</span></a></li>';
			$infos  .= '</ul>';
			$infos  .= '<figcaption>';
			$infos  .= '</figure>';
			$infos  .= '</div>';
			
			$infos2  = '<div class="inbl">';
			$infos2 .= '<h3><span>'.__('Keep the plugin free', 'jm-tc').'</span></h3>';
			$infos2 .= '<p>'.__('Please help if you want to keep this plugin free.', 'jm-tc').'</p>';
			$infos2	.= '
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="'.$donation.'">
						<input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
						</form>
						';
			$infos2 .= '</div>';

			$infos3  = '<h3><span>'.__('Plugin', 'jm-tc').'</span></h3>';
			$infos3 .= '<p>';
			$infos3 .= __('Maybe you will like this plugin too: ', 'jm-tc') . self::get_plugins_list( $slugs );
			$infos3 .= '</p>';
			
			
			echo $infos.$infos2.$infos3;
		}
	}
}