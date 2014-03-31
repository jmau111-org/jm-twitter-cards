<?php
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if( ! class_exists('JM_TC_Utilities') ) {

	Class JM_TC_Utilities {


		public static function remove_at($at)
		{
			$noat = str_replace('@', '', $at);
			return $noat;
		}

		// Remove any line-breaks

		public static function remove_lb($lb)
		{
			$output = str_replace(array(
			"\r\n",
			"\r"
			) , "\n", $lb);
			$lines = explode("\n", $output);
			$nolb = array();
			foreach($lines as $key => $line)
			{
				if (!empty($line)) $nolb[] = trim($line);
			}

			return implode($nolb);
		}

		// Use of a WP 3.6 function has_shortcode and fallback

		public static function has_shortcode($content, $tag)
		{
			if (function_exists('has_shortcode'))
			{ //in this case we are in 3.6 at least
				return has_shortcode($content, $tag);
			}
			else
			{
				global $shortcode_tags;
				return array_key_exists($tag, $shortcode_tags);
				preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
				if (empty($matches)) return false;
				foreach($matches as $shortcode)
				{
					if ($tag === $shortcode[2]) return true;
				}
			}

			return false;
		}
		
		
		public static function get_excerpt_by_id($post_id)
		{
			$the_post = get_post($post_id);
			$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

			// SET LENGTH
			$opts = get_option('jm_tc_options');
			$excerpt_length = $opts['twitterCardExcerptLength'];
			
			$the_excerpt = strip_shortcodes($the_excerpt);
			$the_excerpt = wp_trim_words( $the_excerpt, $excerpt_length, '');// it's better to use wp functions 
			
			return esc_attr($the_excerpt); // to prevent meta from being broken by ""
		}
		
		
		
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