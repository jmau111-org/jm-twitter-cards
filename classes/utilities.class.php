<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists('JM_TC_Utilities')) {

    class JM_TC_Utilities{

        /*
        *   Remove "at" from input
        *	@since 1.0
        *   @param string $at
        *   @return string
        */
        public static function remove_at($at){
            $noat = str_replace('@', '', $at);
            return $noat;
        }

        /*
        *   Remove line breaks
        *	@since 5.3.2
        *   @param string $lb
        *   @return string
        */
        public static function remove_lb($lb){
            $output = str_replace(array(
                "\r\n",
                "\r"
            ), "\n", $lb);
            $lines = explode("\n", $output);
            $nolb = array();
            foreach ($lines as $key => $line) {
                if (!empty($line)) $nolb[] = trim($line);
            }

            return implode($nolb);
        }

        /*
        *   Get excerpt by post ID and filter shortcodes, tags and special chars
        *	@since 5.3.2
        *   @param integer $post_id
        *   @return string
        */
        public static function get_excerpt_by_id($post_id){
            $the_post = get_post($post_id);
            $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

            //kill shortcode
            $shortcode_pattern = get_shortcode_regex();
            $the_excerpt = preg_replace('/' . $shortcode_pattern . '/', '', $the_excerpt);

            // kill tags
            $the_excerpt = strip_tags($the_excerpt);

            return esc_attr(substr($the_excerpt, 0, 200)); // to prevent meta from being broken by e.g ""
        }

        /*
        *   Get tutorials
        *	@since 5.3.2
        *   @param array $data Array containing video IDs
        *   @param string $provider endpoint API
        *   @return string
        */
        public static function display_footage($data, $provider = 'http://www.youtube.com/watch?v='){

            $output = '';

            if (is_array($data)) {
                foreach ($data as $label => $id)
                    $output .= '<div class="inbl"><h3 id="' . $id . '">' . $label . '</h3>' . '<p>' . wp_oembed_get(esc_url($provider . $id)) . '</p></div>';
            }

            return $output;
        }

    }

}
