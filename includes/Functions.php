<?php

namespace JMTC;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

trait Functions
{
    private function maybe_get_opt($key, $array = []): string
    {
        $array = !empty($array) ? $array : $this->get_options();
        return array_key_exists($key, $array) ? $array[$key] : '';
    }

    private function get_default_options(): array
    {
        return [
            'twitterCardType'       => 'summary',
            'twitterCreator'        => '',
            'twitterSite'           => '',
            'twitterImage'          => JM_TC_URL . "admin/img/Twitter_logo_blue.png",
            'twitterImageAlt'       => '',
            'twitterProfile'        => 'yes',
            'twitterPostPageTitle'  => get_bloginfo('name'), // filter used by plugin to customize title
            'twitterPostPageDesc'   => __('Welcome to', 'jm-tc') . ' ' . get_bloginfo('name') . ' - ' . __('see blog posts', 'jm-tc'),
            'twitterCardTitle'      => '',
            'twitterCardDesc'       => '',
            'twitterCardExcerpt'    => 'no',
            'twitterUsernameKey'    => 'twitter',
            'twitteriPhoneName'     => '',
            'twitteriPadName'       => '',
            'twitterGooglePlayName' => '',
            'twitteriPhoneUrl'      => '',
            'twitteriPadUrl'        => '',
            'twitterGooglePlayUrl'  => '',
            'twitteriPhoneId'       => '',
            'twitteriPadId'         => '',
            'twitterGooglePlayId'   => '',
            'twitterCardRobotsTxt'  => 'yes',
            'twitterAppCountry'     => '',
            'twitterCardOg'         => 'no',
        ];
    }

    private function get_options(): array
    {
        global $options;
        $options = get_option(JM_TC_SLUG_MAIN_OPTION);
        $options = (array) apply_filters('jm_tc_get_options', $options);
        $default_options = $this->get_default_options();

        return array_merge($default_options, $options);
    }

    private function remove_at($at): string
    {
        return !is_string($at) ? '' : str_replace('@', '', $at);
    }

    private function remove_lb($lb): ?string
    {
        $output = str_replace(["\r" . PHP_EOL, "\r"], PHP_EOL, $lb);
        $lines  = explode(PHP_EOL, $output);
        $nolb   = [];
        foreach ($lines as $key => $line) {
            if (!empty($line)) {
                $nolb[] = trim($line);
            }
        }

        return implode($nolb);
    }

    private function get_excerpt_by_id($post_id): ?string
    {
        $the_post    = get_post($post_id);
        $the_excerpt = !empty($the_post->post_excerpt) ? $the_post->post_excerpt : $the_post->post_content;
        $shortcode_pattern = get_shortcode_regex();
        $the_excerpt       = preg_replace('/' . $shortcode_pattern . '/', '', $the_excerpt);
        $the_excerpt = strip_tags($the_excerpt);

        return esc_attr(substr($the_excerpt, 0, 200));
    }

    private function gutenberg_exists(): bool
    {
        global $wp_version;
        return (bool) apply_filters('gutenberg_exists', !class_exists('DisableGutenberg') && !class_exists('Classic_Editor') && (function_exists('the_gutenberg_project') || version_compare($wp_version, '5.0', '>=')));
    }

    private function get_post_types(): array
    {
        $cpts = get_option(JM_TC_SLUG_CPT_OPTION);
        return (empty($cpts['twitterCardPt'])) ? get_post_types(['private' => true]) : array_values($cpts['twitterCardPt']);
    }

    private function get_current_post_type(): ?string
    {
        $post_type = get_post_type();

        if (!isset($_GET['post_type'])) {
            $post_type = 'post';
        } elseif (in_array($_GET['post_type'], get_post_types(['show_ui' => true]))) {
            $post_type = $_GET['post_type'];
        }
        return $post_type;
    }

    private function embed($id, $args = []): ?string
    {

        $merged = wp_parse_args(
            $args,
            [
                'witdh' => 400,
            ]
        );

        return wp_oembed_get(esc_url(sprintf('https://vimeo.com/%s', $id)), $merged);
    }

    private function get_assets_suffix(): string
    {
        return defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    }

    private function get_assets_version($file_rel_path): string
    {
        return (file_exists($file_rel_path)) ? JM_TC_VERSION . "-" . filemtime($file_rel_path) : JM_TC_VERSION;
    }

    private function get_videos(): array
    {
        return [
            '302609444' => esc_html__('Setup for the first time', 'jm-tc'),
            '302609402' => esc_html__('Setup metabox with custom post types', 'jm-tc'),
            '302609437' => esc_html__('Dealing with images', 'jm-tc'),
            '302609425' => esc_html__('Set first image found in post content as twitter image', 'jm-tc'),
            '302609429' => esc_html__('Upgrading to Gutenberg', 'jm-tc'),
            '305338709' => esc_html__('How to recover twitter cards sidebar after unpin', 'jm-tc'),
        ];
    }
}
