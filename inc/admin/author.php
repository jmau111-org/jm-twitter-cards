<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists('JM_TC_Author') ) {

	class JM_TC_Author extends JM_TC_Utilities{

		public static function get_plugins_list( $slugs = array() ) {
			
			$list = '<ul>';

			foreach ( $slugs as $slug => $name ){
				
				$list .= '<li><a target="_blank" href="http://wordpress.org/plugins/'.$slug.'">'.__( $name, 'jm-tc').'</a></li>';
			}
			
			$list .= '</ul>';
			return $list;
			
		}
		
		public static function get_author_infos() {

			$infos   = '<div class="postbox" style="margin:2em 0; padding:2em;">';
			$infos  .= '<h1 class="hndle">'.__('About the developer', 'jm-tc').'</h1>';
			$infos  .= '<p><img src="http://www.gravatar.com/avatar/'.md5('tweetpressfr' . '@' . 'gmail' . '.' . 'com').'" style="float:left;margin-right:10px;"/>';
			$infos  .= '<strong>Julien Maury</strong>' ."\n";
			$infos  .= __('I am a WordPress Developer, I like to make it simple.', 'jm-tc').'<br/>';
			$infos  .= '<a href="http://www.tweetpress.fr" target="_blank" title="TweetPress.fr - WordPress and Twitter tips">www.tweetpress.fr</a>' .'<br/>';
			$infos  .= '<i class="link-like dashicons dashicons-twitter"></i> <a href="http://twitter.com/intent/user?screen_name=tweetpressfr" >@TweetPressFR</a>';
			$infos  .= '</p>';
			
			$infos2  =  '<h2 style="margin:2em 0 1em;"><span>'.__('Help me keep this free', 'jm-tc').'</span></h2>';
			$infos2 .= '<p>'.__('Please help me keep this plugin free.', 'jm-tc').'</p>';
			$infos2	.= '<i class="link-like va-bottom dashicons dashicons-cart"></i><a target="_blank" href="http://www.amazon.fr/registry/wishlist/1J90JNIHBBXL8">'.__('WishList Amazon', 'jm-ltsc').'</a>';
			
			
			
			$slugs = array(
			'jm-dashicons-shortcode' 			=> 'JM Dashicons Shortcode',
			'jm-last-twit-shortcode' 			=> 'JM Last Twit Shortcode',
			'jm-twit-this-comment'				=> 'JM Twit This Comment',
			'jm-simple-qr-code-widget' 			=> 'JM Simple QR Code Widget',
			'jm-html5-and-responsive-gallery' 	=> 'JM HTML5 Responsive Gallery',
			);
			

			$infos3  = '<h2 style="margin:2em 0 1em;"><span>'.__('Plugin', 'jm-tc').'</span></h2>';
			$infos3 .= '<p>';
			$infos3 .= __('Maybe you will like this plugin too: ', 'jm-tc') . static::get_plugins_list( $slugs );
			$infos3 .= '</p>';
			$infos3 .= '</div>';
			
			
			echo $infos.$infos2.$infos3;
		}
	}
}