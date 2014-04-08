<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( class_exists('JM_TC_Utilities') ) {

	class JM_TC_Author extends JM_TC_Utilities{

		public static function get_plugins_list( $slugs = array() ) {
			
			$list = '<ul>';

			foreach ( $slugs as $slug => $name ){
				
				$list .= '<li><a target="_blank" href="http://wordpress.org/plugins/'.$slug.'">'.__( $name, 'jm-tc').'</a></li>';
			}
			
			$list .= '</ul>';
			return $list;
			
		}
		
		public static function get_author_infos($name, $desc, $gravatar_email, $url, $donation, $twitter, $slugs = array() ) {

			$infos   = '<div class="postbox" style="margin:2em 0; padding:2em;">';
			$infos  .= '<h1 class="hndle">'.__('The developer', 'jm-tc').'</h1>';
			$infos  .= '<p><img src="http://www.gravatar.com/avatar/'.md5($gravatar_email).'" style="float:left;margin-right:10px;"/>';
			$infos  .= '<h2>'.$name.'</h2>';
			$infos  .= '<blockquote>'.$desc.'</blockquote>' .'<br/>';
			$infos  .= '<a href="'.$url.'" target="_blank">'.$url.'</a>' ."\n\n";
			$infos  .= '<i class="link-like dashicons dashicons-twitter"></i> <a href="http://twitter.com/intent/user?screen_name='.$twitter.'" >@'.$twitter.'</a>';
			$infos  .= '</p>';
			
			$infos2  =  '<h2 style="margin:2em 0 1em;"><span>'.__('Keep the plugin free', 'jm-tc').'</span></h2>';
			$infos2 .= '<p>'.__('Please help if you want to keep this plugin free.', 'jm-tc').'</p>';
			$infos2	.= '<i class="link-like va-bottom dashicons dashicons-cart"></i><a target="_blank" href="'.$donation.'">'.__('Donation', 'jm-ltsc').'</a>';
			

			$infos3  = '<h2 style="margin:2em 0 1em;"><span>'.__('Plugin', 'jm-tc').'</span></h2>';
			$infos3 .= '<p>';
			$infos3 .= __('Maybe you will like this plugin too: ', 'jm-tc') . static::get_plugins_list( $slugs );
			$infos3 .= '</p>';
			$infos3 .= '</div>';
			
			
			echo $infos.$infos2.$infos3;
		}
	}
}