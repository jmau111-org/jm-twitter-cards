<?php

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!function_exists('jm_tc_maybe_get_opt')) 
{
    function jm_tc_maybe_get_opt($key, $array = []): string
    {
        $array = !empty($array) ? $array : jm_tc_get_options();
        return array_key_exists($key, $array) ? $array[$key] : '';
    }
}

if (!function_exists('jm_tc_get_default_options')) 
{
    function jm_tc_get_default_options(): array
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
            'twitterUsernameKey'    => 'jm_tc_twitter',
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
}

if (!function_exists('jm_tc_get_options')) 
{
    function jm_tc_get_options(): array
    {
        global $jm_tc_options;
        $jm_tc_options = get_option(JM_TC_SLUG_MAIN_OPTION);
        $jm_tc_options = (array) apply_filters('jm_tc_get_options', $jm_tc_options);
        $default_options = jm_tc_get_default_options();

        return array_merge($default_options, $jm_tc_options);
    }
}

if (!function_exists('jm_tc_remove_at')) 
{
    function jm_tc_remove_at($at): string
    {
        return !is_string($at) ? '' : str_replace('@', '', $at);
    }
}

if (!function_exists('jm_tc_remove_lb')) 
{
    function jm_tc_remove_lb($lb): ?string
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
}

if (!function_exists('jm_tc_get_excerpt_by_id')) 
{
    function jm_tc_get_excerpt_by_id($post_id): ?string
    {
        $the_post    = get_post($post_id);
        $the_excerpt = !empty($the_post->post_excerpt) ? $the_post->post_excerpt : $the_post->post_content;
        $shortcode_pattern = get_shortcode_regex();
        $the_excerpt       = preg_replace('/' . $shortcode_pattern . '/', '', $the_excerpt);
        $the_excerpt = strip_tags($the_excerpt);

        return esc_attr(substr($the_excerpt, 0, 200));
    }
}

if (!function_exists('jm_tc_gutenberg_exists')) 
{
    function jm_tc_gutenberg_exists(): bool
    {
        global $wp_version;
        return (bool) apply_filters('jm_tc_gutenberg_exists', !class_exists('DisableGutenberg') && !class_exists('Classic_Editor') && (function_exists('the_gutenberg_project') || version_compare($wp_version, '5.0', '>=')));
    }
}

if (!function_exists('jm_tc_get_post_types')) 
{
    function jm_tc_get_post_types(): array
    {
        $cpts = get_option('jm_tc_cpt');
        return (empty($cpts['twitterCardPt'])) ? get_post_types(['public' => true]) : array_values($cpts['twitterCardPt']);
    }
}

if (!function_exists('jm_tc_get_current_post_type')) 
{
    function jm_tc_get_current_post_type(): ?string
    {
        $post_type = get_post_type();

        if (!isset($_GET['post_type'])) {
            $post_type = 'post';
        } elseif (in_array($_GET['post_type'], get_post_types(['show_ui' => true]))) {
            $post_type = $_GET['post_type'];
        }
        return $post_type;
    }
}

if (!function_exists('jm_tc_is_dev_env')) 
{
    function jm_tc_is_dev_env(): bool
    {
        return defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
    }
}
    
if (!function_exists('jm_tc_embed')) 
{
    function jm_tc_embed($id, $args = []): ?string
    {

        $merged = wp_parse_args(
            $args,
            [
                'witdh' => 400,
            ]
        );

        return wp_oembed_get(esc_url(sprintf('https://vimeo.com/%s', $id)), $merged);
    }
}
  
if (!function_exists('jm_tc_get_assets_suffix')) 
{
    function jm_tc_get_assets_suffix(): string
    {
        return jm_tc_is_dev_env() ? '' : '.min';
    }
}
    
if (!function_exists('jm_tc_get_assets_version')) 
{
    function jm_tc_get_assets_version($file_rel_path): string
    {
        return (file_exists($file_rel_path)) ? JM_TC_VERSION . "-" . filemtime($file_rel_path) : JM_TC_VERSION;
    }
}