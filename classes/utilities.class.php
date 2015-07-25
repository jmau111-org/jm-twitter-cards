<?php
namespace TokenToMe\TwitterCards;

if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}


class Utilities
{

    /**
     * @param $at
     *
     * @return bool|mixed
     */
    public static function remove_at($at)
    {

        if (!is_string($at)) {
            return false;
        }

        $noat = str_replace('@', '', $at);

        return $noat;
    }

    /**
     * @param $lb
     *
     * @return string
     */
    public static function remove_lb($lb)
    {
        $output = str_replace(array("\r" . PHP_EOL, "\r"), PHP_EOL, $lb);
        $lines = explode(PHP_EOL, $output);
        $nolb = array();
        foreach ($lines as $key => $line) {
            if (!empty($line)) {
                $nolb[] = trim($line);
            }
        }

        return implode($nolb);
    }

    /**
     * @param $post_id
     *
     * @return string|void
     */
    public static function get_excerpt_by_id($post_id)
    {
        $the_post = get_post($post_id);
        $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt

        //kill shortcode
        $shortcode_pattern = get_shortcode_regex();
        $the_excerpt = preg_replace('/' . $shortcode_pattern . '/', '', $the_excerpt);

        // kill tags
        $the_excerpt = strip_tags($the_excerpt);

        return esc_attr(substr($the_excerpt, 0, 200)); // to prevent meta from being broken by e.g ""
    }

    /**
     * @param $data
     * @param string $provider
     *
     * @return string
     */
    public static function display_footage($data, $provider = 'http://www.youtube.com/watch?v=')
    {

        $output = '';

        if (is_array($data)) {
            foreach ($data as $label => $id) {
                $output .= '<div class="inbl"><h3 id="' . $id . '">' . $label . '</h3>' . wpautop(wp_oembed_get(esc_url($provider . $id))) . '</div>';
            }
        }

        return $output;
    }

}

