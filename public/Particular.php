<?php

namespace JMTC;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Particular
{
    use Functions;

    public function robots_mod($output): string
    {
        $output .= 'User-agent: Twitterbot' . PHP_EOL;
        $output .= 'Disallow: ';

        return $output;
    }

    public function remover($meta): ?string
    {
        if (in_array(strtolower($meta), ['tweetpressfr', '@tweetpressfr', 'jmau111', '@jmau111'], true)) {
            return false;
        }

        return $meta;
    }

    public function new_blog($blog_id): void
    {
        switch_to_blog($blog_id);
        $this->fill_default_options();
        restore_current_blog();
    }

    public function modify_excerpt($excerpt): ?string
    {
        global $post;
        $_excerpt = $this->get_excerpt_from_far_far_away($post->ID);

        if (!empty($_excerpt)) {
            return $_excerpt;
        }

        return $excerpt;
    }

    private function get_excerpt_from_far_far_away($post_id): ?string
    {
        global $wpdb;
        $query        = "SELECT post_excerpt FROM {$wpdb->posts} WHERE ID = %d LIMIT 1";
        $result       = $wpdb->get_results($wpdb->prepare($query, (int) $post_id), ARRAY_A);
        $post_excerpt = $result[0]['post_excerpt'];

        return esc_attr($post_excerpt);
    }
}
