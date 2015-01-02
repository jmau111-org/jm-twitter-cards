<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( ! class_exists('JM_TC_Options') ) {

    class JM_TC_Options{

        /**
         * options
         * @var array
         */
        protected $opts = array();

        /**
         * Constructor
         * @since 5.3.2
         */
        function __construct(){

            $this->opts = jm_tc_get_options();

        }

        /**
         * Retrieve data from famous SEO plugins such as Yoast or All_in_One_SEO
         * @since 5.3.2
         * @param integer $post_ID
         * @param string $type Whether it's post title or desc
         * @return string
         */
        public static function get_seo_plugin_datas($post_ID = false, $type){

            $aioseop_title = get_post_meta($post_ID, '_aioseop_title', true);
            $aioseop_description = get_post_meta($post_ID, '_aioseop_description', true);
            $yoast_wpseo_title = get_post_meta($post_ID, '_yoast_wpseo_title', true);
            $yoast_wpseo_description = get_post_meta($post_ID, '_yoast_wpseo_metadesc', true);

            if (class_exists('WPSEO_Frontend')) {
                $title = !empty($yoast_wpseo_title) ? htmlspecialchars(stripcslashes($yoast_wpseo_title)) : the_title_attribute(array('echo' => false));
                $desc = !empty($yoast_wpseo_description) ? htmlspecialchars(stripcslashes($yoast_wpseo_description)) : JM_TC_Utilities::get_excerpt_by_id($post_ID);

            } elseif (class_exists('All_in_One_SEO_Pack')) {
                $title = !empty($aioseop_title) ? htmlspecialchars(stripcslashes($aioseop_title)) : the_title_attribute(array('echo' => false));
                $desc = !empty($aioseop_description) ? htmlspecialchars(stripcslashes($aioseop_description)) : JM_TC_Utilities::get_excerpt_by_id($post_ID);

            } else {
                $title = the_title_attribute(array('echo' => false));
                $desc = JM_TC_Utilities::get_excerpt_by_id($post_ID);
            }

            switch ($type) {

                case "title" :
                    $data = $title;
                    break;

                case "desc" :
                    $data = $desc;
                    break;

            }

            return $data;

        }


        /*
        * Retrieve the meta card type
        * @since 5.3.2
        * @param integer $post_ID
        * @return array
        */
        public function cardType($post_ID = false){

            $cardTypePost = get_post_meta($post_ID, 'twitterCardType', true);

            $cardType = (!empty($cardTypePost)) ? $cardTypePost : $this->opts['twitterCardType'];

            return array('card' => apply_filters('jm_tc_card_type', $cardType));
        }

        /*
        * Retrieve the meta creator
        * @since 5.3.2
        * @param bool $post_author Whether author is different from global setting or not
        * @param integer $post_ID
        * @return array
        */
        public function creatorUsername($post_author = false, $post_ID = false){

            $post = get_post($post_ID);
            $author_id = $post->post_author;

            if ($post_author) {

                //to be modified or left with the value 'jm_tc_twitter'

                $cardUsernameKey = $this->opts['twitterUsernameKey'];
                $cardCreator = get_the_author_meta($cardUsernameKey, $author_id);

                $cardCreator = (!empty($cardCreator)) ? $cardCreator : $this->opts['twitterCreator'];
                $cardCreator = '@' . JM_TC_Utilities::remove_at($cardCreator);

            } else {

                $cardCreator = '@' . JM_TC_Utilities::remove_at($this->opts['twitterCreator']);
            }


            return array('creator' => apply_filters('jm_tc_card_creator', $cardCreator));
        }

        /*
        * retrieve the meta site
        * @since 5.3.2
        * @return array
        */
        public function siteUsername(){

            $cardSite = '@' . JM_TC_Utilities::remove_at($this->opts['twitterSite']);

            return array('site' => apply_filters('jm_tc_card_site', $cardSite));
        }


        /*
        * retrieve the title
        * @return array
        */
        public function title($post_ID = false){

            if ($post_ID) {

                if (!empty($this->opts['twitterCardTitle'])) {

                    $title = get_post_meta($post_ID, $this->opts['twitterCardTitle'], true); // this one is pretty hard to debug ^^
                    $cardTitle = !empty($title) ? htmlspecialchars(stripcslashes($title)) : the_title_attribute(array('echo' => false));

                } elseif (empty($this->opts['twitterCardTitle']) && (class_exists('WPSEO_Frontend') || class_exists('All_in_One_SEO_Pack'))) {

                    $cardTitle = self::get_seo_plugin_datas($post_ID, 'title');

                } else {

                    $cardTitle = the_title_attribute(array('echo' => false));

                }

            } else {

                $cardTitle = get_bloginfo('name');

            }

            return array('title' => apply_filters('jm_tc_get_title', $cardTitle));

        }

        /*
        * retrieve the description
        * @param integer $post_ID
        * @since 5.3.2
        * @return array
        */
        public function description($post_ID = false){

            if ($post_ID) {


                if (!empty($this->opts['twitterCardDesc'])) {

                    $desc = get_post_meta($post_ID, $this->opts['twitterCardDesc'], true);
                    $cardDescription = !empty($desc) ? htmlspecialchars(stripcslashes($desc)) : JM_TC_Utilities::get_excerpt_by_id($post_ID);

                } elseif (empty($this->opts['twitterCardDesc']) && (class_exists('WPSEO_Frontend') || class_exists('All_in_One_SEO_Pack'))) {

                    $cardDescription = self::get_seo_plugin_datas($post_ID, 'desc');

                } else {

                    $cardDescription = JM_TC_Utilities::get_excerpt_by_id($post_ID);

                }

            } else {

                $cardDescription = $this->opts['twitterPostPageDesc'];
            }


            $cardDescription = JM_TC_Utilities::remove_lb($cardDescription);

            return array('description' => apply_filters('jm_tc_get_excerpt', $cardDescription));

        }


        /*
        * retrieve the images
        * @param integer $post_ID
        * @return array
        */

        public function image($post_ID = false){

            $cardImage = get_post_meta($post_ID, 'cardImage', true);

            //gallery
            if (($cardType = get_post_meta($post_ID, 'twitterCardType', true)) != 'gallery') {
                if (get_the_post_thumbnail($post_ID) != '') {
                    if (!empty($cardImage)) { // cardImage is set
                        $image = $cardImage;
                    } else {
                        $size = JM_TC_Thumbs::thumbnail_sizes($post_ID);
                        $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($post_ID), $size);
                        $image = $image_attributes[0];
                    }

                } elseif (get_the_post_thumbnail($post_ID) == '' && !empty($cardImage)) {
                    $image = $cardImage;
                } elseif ('attachment' == get_post_type()) {

                    $image = wp_get_attachment_url($post_ID);
                } elseif ($post_ID == false) {

                    $image = $this->opts['twitterImage'];
                } else {
                    //fallback
                    $image = $this->opts['twitterImage'];
                }

                //In case Open Graph is on

                $img_meta = ($this->opts['twitterCardOg'] == 'yes') ? 'image' : 'image:src';

                return array($img_meta => apply_filters('jm_tc_image_source', $image));

            } else { // markup will be different

                global $post;

                if (is_a($post, 'WP_Post')
                    && function_exists('has_shortcode')
                ) {

                    if (has_shortcode($post->post_content, 'gallery')) {

                        $query_img = get_post_gallery() ? get_post_gallery($post_ID, false) : array();//no backward compatibility before 3.6

                        $pic = array();
                        $i = 0;

                        foreach ($query_img['src'] as $img) {

                            // get attachment array with the ID from the returned posts

                            $pic['image' . $i . ':src'] = $img;

                            $i++;
                            if ($i > 3) break; //in case there are more than 4 images in post, we are not allowed to add more than 4 images in our card by Twitter

                        }

                        return $pic;

                    } else {
                        return self::error(__('Warning : Gallery Card is not set properly ! There is no gallery in this post !', JM_TC_TEXTDOMAIN));
                    }

                }

            }

        }


        /*
        * Product additional fields
        * @param integer $post_ID
        * @return array
        */
        public function product($post_ID){

            $cardType = apply_filters('jm_tc_card_type', get_post_meta($post_ID, 'twitterCardType', true));

            if ($cardType == 'product') {

                $data1 = get_post_meta($post_ID, 'cardData1', true);
                $label1 = get_post_meta($post_ID, 'cardLabel1', true);
                $data2 = get_post_meta($post_ID, 'cardData2', true);
                $label2 = get_post_meta($post_ID, 'cardLabel2', true);


                if (!empty($data1) && !empty($label1) && !empty($data2) && !empty($label2)) {
                    return array(
                        'data1' => apply_filters('jm_tc_product_field-data1', $data1),
                        'label1' => apply_filters('jm_tc_product_field-label1', $label1),
                        'data2' => apply_filters('jm_tc_product_field-data2', $data2),
                        'label2' => apply_filters('jm_tc_product_field-label2', $label2)
                    );
                } else {
                    return self::error(__('Warning : Product Card is not set properly ! There is no product datas !', JM_TC_TEXTDOMAIN));
                }

            } else {
                return;
            }
        }

        /*
        * Player additional fields
        * @param integer $post_ID
        * @return array
        */
        public function player($post_ID){

            $cardType = apply_filters('jm_tc_card_type', get_post_meta($post_ID, 'twitterCardType', true));

            if ($cardType == 'player') {

                $playerUrl = get_post_meta($post_ID, 'cardPlayer', true);
                $playerStreamUrl = get_post_meta($post_ID, 'cardPlayerStream', true);
                $playerWidth = get_post_meta($post_ID, 'cardPlayerWidth', true);
                $playerHeight = get_post_meta($post_ID, 'cardPlayerHeight', true);
                $player = array();

                //Player
                if (!empty($playerUrl)) {
                    $player['player'] = apply_filters('jm_tc_player_url', $playerUrl);
                } else {
                    return self::error(__('Warning : Player Card is not set properly ! There is no URL provided for iFrame player !', JM_TC_TEXTDOMAIN));
                }

                //Player stream
                if (!empty($playerStreamUrl)) {

                    $codec = "video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;";

                    $player['player:stream'] = apply_filters('jm_tc_player_stream_url', $playerStreamUrl);
                    $player['player:stream:content_type'] = apply_filters('jm_tc_player_codec', $codec);

                }

                //Player width and height
                if (!empty($playerWidth) && !empty($playerHeight)) {
                    $player['player:width'] = apply_filters('jm_tc_player_width', $playerWidth);
                    $player['player:height'] = apply_filters('jm_tc_player_height', $playerHeight);

                } else {
                    $player['player:width'] = apply_filters('jm_tc_player_default_width', 435);
                    $player['player:height'] = apply_filters('jm_tc_player_default_height', 251);
                }

                return $player;

            } else {
                return;
            }

        }


        /*
        * Image Width and Height
        * @param integer $post_ID
        * @return array
        */

        public function cardDim($post_ID = false){

            $cardTypePost = get_post_meta($post_ID, 'twitterCardType', true);
            $cardWidth = get_post_meta($post_ID, 'cardImageWidth', true);
            $cardHeight = get_post_meta($post_ID, 'cardImageHeight', true);
            $type = (!empty($cardTypePost)) ? $cardTypePost : $this->opts['twitterCardType'];


            if (in_array($type, array('photo', 'product', 'summary_large_image', 'player'))) {

                $width = (!empty($cardWidth)) ? $cardWidth : $this->opts['twitterImageWidth'];
                $height = (!empty($cardHeight)) ? $cardHeight : $this->opts['twitterImageHeight'];

                return array(
                    'image:width' => apply_filters('jm_tc_image_width', $width),
                    'image:height' => apply_filters('jm_tc_image_height', $height)
                );

            } elseif (in_array($type, array('photo', 'product', 'summary_large_image', 'player')) && !$post_ID) {

                return array(
                    'image:width' => $this->opts['twitterCardWidth'],
                    'image:height' => $this->opts['twitterCardHeight']
                );
            } else {
                return;
            }


        }


        /*
        * retrieve the deep linking and app install meta
        * @since 5.3.2
        * @return array
        */
        public function deeplinking(){

            $twitteriPhoneName = (!empty($this->opts['twitteriPhoneName'])) ? $this->opts['twitteriPhoneName'] : '';
            $twitteriPadName = (!empty($this->opts['twitteriPadName'])) ? $this->opts['twitteriPadName'] : '';
            $twitterGooglePlayName = (!empty($this->opts['twitterGooglePlayName'])) ? $this->opts['twitterGooglePlayName'] : '';
            $twitteriPhoneUrl = (!empty($this->opts['twitteriPhoneUrl'])) ? $this->opts['twitteriPhoneUrl'] : '';
            $twitteriPadUrl = (!empty($this->opts['twitteriPadUrl'])) ? $this->opts['twitteriPadUrl'] : '';
            $twitterGooglePlayUrl = (!empty($this->opts['twitterGooglePlayUrl'])) ? $this->opts['twitterGooglePlayUrl'] : '';
            $twitteriPhoneId = (!empty($this->opts['twitteriPhoneId'])) ? $this->opts['twitteriPhoneId'] : '';
            $twitteriPadId = (!empty($this->opts['twitteriPadId'])) ? $this->opts['twitteriPadId'] : '';
            $twitterGooglePlayId = (!empty($this->opts['twitterGooglePlayId'])) ? $this->opts['twitterGooglePlayId'] : '';
            $twitterAppCountry = (!empty($this->opts['twitterAppCountry'])) ? $this->opts['twitterAppCountry'] : '';


            return array(
                'app:name:iphone' => apply_filters('jm_tc_iphone_name', $twitteriPhoneName),
                'app:name:ipad' => apply_filters('jm_tc_ipad_name', $twitteriPhoneName),
                'app:name:googleplay' => apply_filters('jm_tc_googleplay_name', $twitterGooglePlayName),
                'app:url:iphone' => apply_filters('jm_tc_iphone_url', $twitteriPhoneUrl),
                'app:url:ipad' => apply_filters('jm_tc_ipad_url', $twitteriPadUrl),
                'app:url:googleplay' => apply_filters('jm_tc_googleplay_url', $twitterGooglePlayUrl),
                'app:id:iphone' => apply_filters('jm_tc_iphone_id', $twitteriPhoneId),
                'app:id:ipad' => apply_filters('jm_tc_ipad_id', $twitteriPadId),
                'app:id:googleplay' => apply_filters('jm_tc_googleplay_id', $twitterGooglePlayId),
                'app:id:country' => apply_filters('jm_tc_country', $twitterAppCountry)
            );


        }


        /*
        * error in config
        * @return string
        */
        protected function error($error = false){

            if ($error && current_user_can('edit_posts'))
                return $error;
            else return;

        }


    }


}