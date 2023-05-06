<?php

namespace JMTC;

use JMTC\Admin\MetaValues;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Front
{
    protected $tc_opts;
    private $mvi;

    use Functions;

    public function __construct(MetaValues $meta_v) {
        $this->tc_opts = $this->get_options();
        $this->mvi = $meta_v;
    }

    public function display_meta_tags(): void
    {
        $this->add_html_markup($this->mvi->get_values());
    }

    public function display_home_meta_tags(): void
    {
        $this->add_html_markup([
            'card'        => $this->maybe_get_option('twitterCardType', $this->tc_opts),
            'creator'     => "@" . $this->remove_at($this->maybe_get_option('twitterCreator', $this->tc_opts)),
            'site'        => "@" . $this->remove_at($this->maybe_get_option('twitterSite', $this->tc_opts)),
            'title'       => $this->maybe_get_option('twitterPostPageTitle', $this->tc_opts),
            'description' => $this->maybe_get_option('twitterPostPageDesc', $this->tc_opts),
            'image'       => $this->maybe_get_option('twitterImage', $this->tc_opts),
            'image:alt'   => $this->maybe_get_option('twitterImageAlt', $this->tc_opts),
        ]);
    }

    private function get_html_comments(): string
    {
        return ((bool) apply_filters("jm_tc_display_html_comments", true )) === true ? '<!--|| # JM Twitter Cards v' . JM_TC_VERSION . '  ||-->' . PHP_EOL : "";
    }

    private function add_html_markup(array $data): void
    {
        $markup = '';
        $data = array_map('esc_attr', $data);
        $data = array_filter($data);
        $is_og    = 'twitter';
        $name_tag = 'name';

        echo $this->get_html_comments();

        foreach ($data as $name => $value) {
            if (empty($value)) {
                continue;
            }

            if ('@' === $value) {
                echo sprintf('<!-- [(-_-)@ <meta %s> : %s @(-_-)] -->', $name, __('Missing critical option !', 'jm-tc')) . PHP_EOL;
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

            echo '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
        }

        echo $this->get_html_comments();
    }
}
