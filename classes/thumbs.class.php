<?php
//http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/media.php#L587

if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists('JM_TC_Thumbs')) {

    class JM_TC_Thumbs{
        /**
         * get size setting and convert it into custom sizes
         * @since 5.3.2
         * @param integer $post_id
         * @return string
         */
        public static function thumbnail_sizes($post_id){
            $opts = jm_tc_get_options();

            $size = $opts['twitterCardImgSize'];

            switch ($size)
            {
            case 'small':
                $twitterCardImgSize = 'jmtc-small-thumb';
                break;

            case 'web':
                $twitterCardImgSize = 'jmtc-max-web-thumb';
                break;

            case 'mobile-non-retina':
                $twitterCardImgSize = 'jmtc-max-mobile-non-retina-thumb';
                break;

            case 'mobile-retina':
                $twitterCardImgSize = 'jmtc-max-mobile-retina-thumb';
                break;

            default:
            $twitterCardImgSize = 'jmtc-small-thumb';
            ?><!-- @(-_-)] --><?php
            }

            return $twitterCardImgSize;
        }

        /**
         * Get post thumb weight
         * @return string
         * @param integer $post_id
         */
        public static function get_post_thumbnail_weight($post_id){

            $file_size = has_post_thumbnail($post_id) ? filesize(get_attached_file(get_post_thumbnail_id($post_id))) : 0;//avoid warning if you screw your install or delete all images in upload
            $math = $file_size / 1000000;
            // I was told this is not an accurate math but I actually we do not care,
            // 1 MB images on a website that's not web safe that's insane!


            if ($math == 0) {

                $weight = __('No featured image for now !', 'jm-tc');

            } elseif ($math > 1) {

                $weight = '<span class="error">' . __('Image is heavier than 1MB ! Card will be broken !', 'jm-tc') . '</span>';

            } elseif ($math > 0 && $math < 1) {

                $weight = $math . ' MB';

            } else {

                $weight = __('Unknown error !', 'jm-tc');
            }

            return $weight;

        }


    }
}