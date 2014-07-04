<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Author') ) {

	class JM_TC_Author{

		public static function get_plugins_list( $slugs = array() ) {
			
			$list = '<ul>';

			foreach ( $slugs as $slug => $name ){
				
				$list .= '<li><a class="button" target="_blank" href="http://wordpress.org/plugins/'.$slug.'">'.__( $name, 'jm-tc').'</a></li>';
			}
			
			$list .= '</ul>';
			return $list;
			
		}
		
		public static function get_author_infos($name, $desc, $gravatar_email, $url, $donation, $twitter, $slugs = array() ) {

			$infos   = '<h3 class="hndle">'.__('The developer', 'jm-tc').'</h3>';
			$infos  .= '<div class="inbl"><img src="http://www.gravatar.com/avatar/'.md5($gravatar_email).'"/></div>';
			$infos	.= '<div class="inbl">';
			$infos  .= '<p class="sub-bold">'.$name.'</p>';
			$infos  .= '<blockquote class="about">'.$desc.'</blockquote>'."\n\n";
			$infos  .= '<a href="'.$url.'" target="_blank">'.$url.'</a>' ."\n\n";
			$infos  .= '<i class="link-like dashicons dashicons-twitter"></i> <a href="http://twitter.com/intent/user?screen_name='.$twitter.'" >@'.$twitter.'</a>';
			$infos  .= '</p></div>';
			
			$infos2  =  '<h3><span>'.__('Keep the plugin free', 'jm-tc').'</span></h3>';
			$infos2 .= '<p>'.__('Please help if you want to keep this plugin free.', 'jm-tc')."\n\n";
			$infos2	.= '<i class="dashicons dashicons-cart"></i><a target="_blank" href="'.$donation.'">'.__('Donation', 'jm-ltsc').'</a>'.'</p>';
			

			$infos3  = '<h3><span>'.__('Plugin', 'jm-tc').'</span></h3>';
			$infos3 .= '<p>';
			$infos3 .= __('Maybe you will like this plugin too: ', 'jm-tc') . self::get_plugins_list( $slugs );
			$infos3 .= '</p>';
			
			
			echo $infos.$infos2.$infos3;
		}
	}
}