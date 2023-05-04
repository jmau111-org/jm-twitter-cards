<?php

namespace JMTC\Admin;

use JMTC\Functions;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class MetaValues
{
    private $tc_opts;
    private $post_ID;
    private $values;

    use Functions;

    public function __construct(array $tc_opts, int $post_ID)
    {
        $this->post_ID = $post_ID;
        $this->tc_opts = $tc_opts;
        $this->values = [];

        $this->set_card_type()
            ->set_creator_username()
            ->set_site_username()
            ->set_title()
            ->set_description()
            ->set_image()
            ->set_image_alt();

        if ($this->values["card"] === "player") {
            $this->set_player();
        }

        if ($this->values["card"] === "app") {
            $this->set_deep_linking();
        }
    }

    public function get_values(): array
    {
        return $this->values;
    }

    private function set_card_type(): self
    {
        $cardTypePost = get_post_meta($this->post_ID, 'twitterCardType', true);
        $cardType     = (!empty($cardTypePost)) ? $cardTypePost :  $this->maybe_get_option('twitterCardType', $this->tc_opts);

        $this->values['card'] = apply_filters('jm_tc_card_type', $cardType, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_creator_username($post_author = false): self
    {
        $post_obj    = get_post($this->post_ID);
        $author_id   = $post_obj->post_author;
        $crea        = !empty($this->tc_opts['twitterCreator'])
            ? $this->tc_opts['twitterCreator']
            : '';
        $cardCreator = '@' .  $this->remove_at($crea);

        if ($post_author) {
            $cardUsernameKey = !empty($this->tc_opts['twitterUsernameKey']) ? $this->tc_opts['twitterUsernameKey'] : 'jm_tc_twitter';
            $cardCreator     = get_the_author_meta($cardUsernameKey, $author_id);

            $cardCreator = (!empty($cardCreator))
                ? $cardCreator
                : $crea;
            $cardCreator = '@' .  $this->remove_at($cardCreator);
        }

        $this->values['creator'] = apply_filters('jm_tc_card_creator', $cardCreator, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_site_username(): self
    {
        $cardSite = '@' .  $this->remove_at($this->maybe_get_option('twitterSite', $this->tc_opts));
        $this->values['site'] = apply_filters('jm_tc_card_site', $cardSite, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_title(): self
    {
        $cardTitle = get_bloginfo('name');

        if ($this->post_ID) {

            $cardTitle = get_the_title($this->post_ID);

            if (class_exists('WPSEO_Frontend') || class_exists('All_in_One_SEO_Pack')) {
                $cardTitle = $this->get_seo_plugin_data('title');
            }

            $cardTitleMeta = get_post_meta($this->post_ID, 'cardTitle', true);

            if (!empty($cardTitleMeta)) {
                $cardTitle = $cardTitleMeta;
            }
        }

        $this->values['title'] = apply_filters('jm_tc_get_title', $cardTitle, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_description(): self
    {
        $cardDescription = !empty($this->tc_opts['twitterPostPageDesc'])
            ? $this->tc_opts['twitterPostPageDesc']
            : '';
        if ($this->post_ID) {

            $cardDescription = $this->get_excerpt_by_id($this->post_ID);

            if (class_exists('WPSEO_Frontend') || class_exists('All_in_One_SEO_Pack')) {
                $cardDescription = $this->get_seo_plugin_data('desc');
            }
        }

        $cardDesc = get_post_meta($this->post_ID, 'cardDesc', true);

        if (!empty($cardDesc)) {
            $cardDescription = $cardDesc;
        }

        $cardDescription = $this->remove_lb($cardDescription);

        $this->values['description'] = apply_filters('jm_tc_get_excerpt', $cardDescription, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_image(): self
    {
        $cardImage   = get_post_meta($this->post_ID, 'cardImage', true);
        $cardImageID = get_post_meta($this->post_ID, 'cardImageID', true);

        if (!empty($cardImageID)) {
            $cardImage = wp_get_attachment_image_url($cardImageID, $this->maybe_get_option('twitterImage', $this->tc_opts));
        }

        if ($this->post_ID && empty($cardImage) && has_post_thumbnail($this->post_ID)) {
            $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($this->post_ID), 'full');
            $image            = !empty($image_attributes) && is_array($image_attributes) ? reset($image_attributes) : $image;
        } elseif (!empty($cardImage)) {
            $image = esc_url_raw($cardImage);
        } elseif ('attachment' === get_post_type()) {
            $image = wp_get_attachment_url($this->post_ID);
        } elseif (empty($this->post_ID)) {
            $image = $this->maybe_get_option('twitterImage', $this->tc_opts);
        }

        $image = !empty($image) ? $image : $this->maybe_get_option('twitterImage', $this->tc_opts);

        $this->values['image'] = apply_filters('jm_tc_image_source', $image, $this->post_ID, $this->tc_opts);

        return $this;
    }

    /**
     * @link https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
     */
    private function set_image_alt(): self
    {
        $cardImageAlt = '';

        if ($this->post_ID) {
            $imageAlt     = get_post_meta($this->post_ID, 'cardImageAlt', true);
            $cardImageAlt = !empty($imageAlt) ? htmlspecialchars(stripcslashes($imageAlt)) : '';
        }

        if (is_home() || is_front_page()) {
            $cardImageAlt = $this->maybe_get_option('twitterImageAlt', $this->tc_opts);
        }

        $cardImageAlt = $this->remove_lb($cardImageAlt);

        $this->values['image:alt'] = apply_filters('jm_tc_image_alt', $cardImageAlt, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_player(): self
    {
        $playerUrl       = get_post_meta($this->post_ID, 'cardPlayer', true);
        $playerWidth     = get_post_meta($this->post_ID, 'cardPlayerWidth', true);
        $playerHeight    = get_post_meta($this->post_ID, 'cardPlayerHeight', true);
        $player          = [];

        $this->values['player'] = apply_filters('jm_tc_player_url', $playerUrl, $this->post_ID, $this->tc_opts);

        if (empty($playerWidth)) {
            $playerWidth  = apply_filters('jm_tc_player_default_width', 435);
        }
        if (empty($playerHeight)) {
            $playerHeight = apply_filters('jm_tc_player_default_height', 251);
        }

        $this->values['player:width']  = apply_filters('jm_tc_player_width', $playerWidth, $this->post_ID, $this->tc_opts);
        $this->values['player:height'] = apply_filters('jm_tc_player_height', $playerHeight, $this->post_ID, $this->tc_opts);

        return $this;
    }

    private function set_deep_linking(): self
    {
        $opts = $this->tc_opts;
        $app = [
            'app:name:iphone'     => apply_filters('jm_tc_iphone_name',  $this->maybe_get_option('twitteriPhoneName', $opts), $this->post_ID, $opts),
            'app:name:ipad'       => apply_filters('jm_tc_ipad_name',  $this->maybe_get_option('twitteriPadName', $opts), $this->post_ID, $opts),
            'app:name:googleplay' => apply_filters('jm_tc_googleplay_name',  $this->maybe_get_option('twitterGooglePlayName', $opts), $this->post_ID, $opts),
            'app:url:iphone'      => apply_filters('jm_tc_iphone_url',  $this->maybe_get_option('twitteriPhoneUrl', $opts), $this->post_ID, $opts),
            'app:url:ipad'        => apply_filters('jm_tc_ipad_url',  $this->maybe_get_option('twitteriPadUrl', $opts), $this->post_ID, $opts),
            'app:url:googleplay'  => apply_filters('jm_tc_googleplay_url',  $this->maybe_get_option('twitterGooglePlayUrl', $opts), $this->post_ID, $opts),
            'app:id:iphone'       => apply_filters('jm_tc_iphone_id',  $this->maybe_get_option('twitteriPhoneId', $opts), $this->post_ID, $opts),
            'app:id:ipad'         => apply_filters('jm_tc_ipad_id',  $this->maybe_get_option('twitteriPadId', $opts), $this->post_ID, $opts),
            'app:id:googleplay'   => apply_filters('jm_tc_googleplay_id',  $this->maybe_get_option('twitterGooglePlayId', $opts), $this->post_ID, $opts),
            'app:id:country'      => apply_filters('jm_tc_country',  $this->maybe_get_option('twitterAppCountry', $opts), $this->post_ID, $opts),
        ];

        $this->deep_links = array_map('esc_attr', $app);

        return $this;
    }

    private function get_seo_plugin_data(string $type): string
    {
        $aioseop_title       = get_post_meta($this->post_ID, '_aioseop_title', true);
        $aioseop_description = get_post_meta($this->post_ID, '_aioseop_description', true);
        $title = !empty(get_the_title($this->post_ID)) ? get_the_title($this->post_ID) : "";
        $desc  = $this->get_excerpt_by_id($this->post_ID);

        if (class_exists('All_in_One_SEO_Pack')) {
            $title = !empty($aioseop_title) ? htmlspecialchars(stripcslashes($aioseop_title)) : the_title_attribute(['echo' => false]);
            $desc  = !empty($aioseop_description) ? htmlspecialchars(stripcslashes($aioseop_description)) : $this->get_excerpt_by_id($this->post_ID);
        }

        return $type === 'desc' ? $desc : $title;
    }
}
