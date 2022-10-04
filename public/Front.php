<?php

namespace TokenToMe\TwitterCards;

use TokenToMe\TwitterCards\Admin\Options;
use TokenToMe\TwitterCards\Utils as Utilities;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Front
{

    /**
     * Options
     * @var array
     */
    protected $opts = [];
    protected $options = [];

    /**
     * Add specific markup
     */
    public function add_markup()
    {

        if (!is_home() && !is_front_page() && !in_array(get_post_type(), Utilities::get_post_types(), true)) {
            return false;
        }

        if (!is_404() && !is_tag() && !is_archive() && !is_tax() && !is_category()) {
            $this->generate_markup();
        }

        return true;
    }

    public function generate_markup()
    {

        $this->options = new Options();
        $this->opts    = \jm_tc_get_options();

        echo $this->html_comments();

        /* most important meta */

        if (is_front_page() || is_home()) {
            echo $this->build(['card' => Utils::maybe_get_opt($this->opts, 'twitterCardType')]);
            echo $this->build(['creator' => "@" . Utilities::remove_at(Utils::maybe_get_opt($this->opts, 'twitterCreator'))]);
            echo $this->build(['site' => "@" . Utilities::remove_at(Utils::maybe_get_opt($this->opts, 'twitterSite'))]);
            echo $this->build(['title' => Utils::maybe_get_opt($this->opts, 'twitterPostPageTitle')]);
            echo $this->build(['description' => Utils::maybe_get_opt($this->opts, 'twitterPostPageDesc')]);
            echo $this->build(['image' => Utils::maybe_get_opt($this->opts, 'twitterImage')]);
            echo $this->build(['image:alt' => Utils::maybe_get_opt($this->opts, 'twitterImageAlt')]);
        } else {
            echo $this->build($this->options->card_type());
            echo $this->build($this->options->creator_username(true));
            echo $this->build($this->options->site_username());
            echo $this->build($this->options->title());
            echo $this->build($this->options->description());
            echo $this->build($this->options->image());
            echo $this->build($this->options->image_alt());
            echo $this->build($this->options->player());
        }

        echo $this->build($this->options->deep_linking());
        echo $this->html_comments(true);
    }

    /**
     * Add just one line before meta
     * @since 5.3.2
     *
     * @param bool $end
     *
     * @return string
     */
    public function html_comments($end = false)
    {
        $slash = (false === $end) ? '' : '/';
        return (bool) apply_filters("jm_tc_display_html_comments", true ) === true ? '<!--||  ' . $slash . 'JM Twitter Cards by Julien Maury v' . JM_TC_VERSION . '  ||-->' . PHP_EOL : "";
    }

    /**
     * @param $data
     *
     * @return string
     */
    protected function build($data)
    {

        $markup = '';
        if (is_array($data)) {

            /**
             * Values are filterable
             * so we need to sanitize again
             */
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

                if (!empty($this->opts['twitterCardOg']) && 'yes' === $this->opts['twitterCardOg'] && in_array($name, [
                    'title',
                    'description',
                    'image',
                ])) {
                    $is_og    = 'og';
                    $name_tag = 'property';
                }

                $markup .= '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . PHP_EOL;
            }
        } elseif (is_string($data)) {
            $markup .= '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . PHP_EOL;
        }

        return $markup;
    }
}
