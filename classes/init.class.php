<?php
//Add some security, no direct load !
defined('ABSPATH')
or die('No direct load !');

if( ! class_exists('JM_TC_Init') ){

    class JM_TC_Init{

        public function __construct(){

            add_action('wpmu_new_blog', array($this, 'new_blog'));
        }


        /**
         * Init meta box
         */
        public static function initialize(){

            if (!class_exists('cmb_Meta_Box')) {

                require_once JM_TC_METABOX_DIR . 'init.php';

            }

            /* Thumbnails */
            $opts = jm_tc_get_options();
            $is_crop = true;
            $crop = $opts['twitterCardCrop'];
            $crop_x = $opts['twitterCardCropX'];
            $crop_y = $opts['twitterCardCropY'];
            $size = $opts['twitterCardImgSize'];

            switch ($crop) {
                case 'yes' :
                    $is_crop = true;
                    break;

                case 'no' :
                    $is_crop = false;
                    break;

                case 'yo' :
                    global $wp_version;
                    $is_crop = (version_compare($wp_version, '3.9', '>=')) ? array($crop_x, $crop_y) : true;
                    break;
            }

            if (function_exists('add_theme_support'))
                add_theme_support('post-thumbnails');

            switch ($size) {
                case 'small':
                    add_image_size('jmtc-small-thumb', 280, 150, $is_crop);/* the minimum size possible for Twitter Cards */
                    break;

                case 'web':
                    add_image_size('jmtc-max-web-thumb', 435, 375, $is_crop);/* maximum web size for photo cards */
                    break;

                case 'mobile-non-retina':
                    add_image_size('jmtc-max-mobile-non-retina-thumb', 280, 375, $is_crop);/* maximum non retina mobile size for photo cards*/
                    break;

                case 'mobile-retina':
                    add_image_size('jmtc-max-mobile-retina-thumb', 560, 750, $is_crop);/* maximum retina mobile size for photo cards  */
                    break;

                default:
                    add_image_size('jmtc-small-thumb', 280, 150, $is_crop);/* the minimum size possible for Twitter Cards */
            }
        }

        /**
         * Default options for multisite when creating new site
         * @param $blog_id
         */
        public static function new_blog($blog_id){
            switch_to_blog($blog_id);

            self::on_activation();

            restore_current_blog();
        }

        /**
         * Avoid undefined index by registering default options
         */
        public static function on_activation(){
            $opts = get_option('jm_tc');
            if (!is_array($opts)) update_option('jm_tc', self::get_default_options());
        }

        /**
         * Return default options
         * @return array
         */
        public static function get_default_options(){
            return array(
                'twitterCardType' => 'summary',
                'twitterCreator' => 'TweetPressFr',
                'twitterSite' => 'TweetPressFr',
                'twitterImage' => 'https://g.twimg.com/Twitter_logo_blue.png',
                'twitterCardImgSize' => 'small',
                'twitterImageWidth' => '280',
                'twitterImageHeight' => '150',
                'twitterCardMetabox' => 'yes',
                'twitterProfile' => 'yes',
                'twitterPostPageTitle' => get_bloginfo('name'), // filter used by plugin to customize title
                'twitterPostPageDesc' => __('Welcome to', 'jm-tc') . ' ' . get_bloginfo('name') . ' - ' . __('see blog posts', 'jm-tc'),
                'twitterCardTitle' => '',
                'twitterCardDesc' => '',
                'twitterCardExcerpt' => 'no',
                'twitterCardCrop' => 'yes',
                'twitterCardCropX' => '',
                'twitterCardCropY' => '',
                'twitterUsernameKey' => 'jm_tc_twitter',
                'twitteriPhoneName' => '',
                'twitteriPadName' => '',
                'twitterGooglePlayName' => '',
                'twitteriPhoneUrl' => '',
                'twitteriPadUrl' => '',
                'twitterGooglePlayUrl' => '',
                'twitteriPhoneId' => '',
                'twitteriPadId' => '',
                'twitterGooglePlayId' => '',
                'twitterCardRobotsTxt' => 'no',
                'twitterAppCountry' => '',
                'twitterCardOg' => 'no',
            );
        }

        /**
         * Avoid undefined index by registering default options
         */
        public static function activate(){

            if (!is_multisite()) {

                self::on_activation();

            } else {

                // For regular options.
                global $wpdb;
                $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                foreach ($blog_ids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::on_activation();
                    restore_current_blog();

                }

            }

        }
    }
}