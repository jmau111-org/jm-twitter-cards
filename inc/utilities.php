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
			$the_post 	 = get_post($post_id);
			$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
			
			$the_excerpt = strip_tags( $the_excerpt );// kill HTML tags
			$the_excerpt = strip_shortcodes( $the_excerpt );// kill shortcodes
			
			
			$the_excerpt = substr( $the_excerpt, 0, 200);// 200 chars at most so 200 chars ^^
			
			return esc_attr($the_excerpt); // to prevent meta from being broken by ""
		}
		

	}
	
}