<?php

namespace JMTC;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Front
{
    protected $tc_opts;

    use Functions;

    public function __construct() {
        $this->tc_opts = $this->get_options();
    }

    public function add_markup(): bool
    {
        if (!is_home() && !is_front_page() && !in_array(get_post_type(), $this->get_post_types(), true)) {
            return false;
        }

        if (!is_404() && !is_tag() && !is_archive() && !is_tax() && !is_category()) {
            $this->display_meta_tags(new Options($this->tc_opts));
        }

        return true;
    }

    private function display_meta_tags(Options $o): void
    {
        $output = $this->html_comments();

        if (is_front_page() || is_home()) {
            $output .= $this->build_home_meta();
        } else {
            $output .= $this->build_meta($o->get_options());
        }

        $output .= $this->html_comments();
        echo $output;
    }

    private function html_comments(): string
    {
        return (bool) apply_filters("jm_tc_display_html_comments", true ) === true ? '<!--|| # JM Twitter Cards v' . JM_TC_VERSION . '  ||-->' . PHP_EOL : "";
    }

    private function build_home_meta(): string
    {
        return $this->build_meta([
            'card'        => $this->maybe_get_option('twitterCardType', $this->tc_opts),
            'creator'     => "@" . $this->remove_at($this->maybe_get_option('twitterCreator', $this->tc_opts)),
            'site'        => "@" . $this->remove_at($this->maybe_get_option('twitterSite', $this->tc_opts)),
            'title'       => $this->maybe_get_option('twitterPostPageTitle', $this->tc_opts),
            'description' => $this->maybe_get_option('twitterPostPageDesc', $this->tc_opts),
            'image'       => $this->maybe_get_option('twitterImage', $this->tc_opts),
            'image:alt'   => $this->maybe_get_option('twitterImageAlt', $this->tc_opts),
        ]);
    }

    private function build_meta($data): string
    {
        $markup = '';
        if (is_array($data)) {
            $data = array_map('esc_attr', $data);
            $data = array_filter($data);
            $is_og    = 'twitter';
            $name_tag = 'name';

            foreach ($data as $name => $value) {
                if (empty($value)) {
                    continue;
                }

                if ('@' === $value) {
                    $markup = sprintf('<!-- [(-_-)@ %s: %s @(-_-)] -->', $name, __('Missing critical option !', 'jm-tc')) . PHP_EOL;
                    continue;
                }

                if (!empty($this->tc_opts['twitterCardOg']) && 'yes' === $this->tc_opts['twitterCardOg'] && in_array($name, [
                    'title',
                    'description',
                    'image',
                ])) {
                    $is_og    = 'og';
                    $name_tag = 'property';
                }

                $markup .= '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
            }
        }

        return $markup;
    }
}
