<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( ! class_exists('JM_TC_Preview') ) {

    class JM_TC_Preview{
        /**
         * output cards preview
         * @return string
         * @param integer $post_ID
         */
        public static function show_preview($post_ID){

            global $jm_twitter_cards;
            $jm_twitter_cards['options'] = new JM_TC_Options;

            $options = $jm_twitter_cards['options'];
            $opts = jm_tc_get_options();

            $is_og = $opts['twitterCardOg'];

            /* most important meta */
            $cardType_arr = $options->cardType($post_ID);
            $creator_arr = $options->creatorUsername(true);
            $site_arr = $options->siteUsername();
            $title_arr = $options->title($post_ID);
            $description_arr = $options->description($post_ID);
            $img_arr = $options->image($post_ID);


            /* secondary meta */
            $product_arr = $options->product($post_ID);
            $player_arr = $options->player($post_ID);
            $deep_link_arr = $options->deeplinking();

            // default
            $app = '';
            $size = 16;
            $class = 'featured-image';
            $tag = 'img';
            $close_tag = '';
            $src = 'src';
            $product_meta = '';
            $styles = '';
            $position = 'position:relative;';
            $hide = '';
            $img = ($is_og == 'yes') ? $img_arr['image'] : $img_arr['image:src'];
            $img_summary = '';
            $gallery_meta = '';

            // particular cases
            if (in_array('summary_large_image', $cardType_arr)) {

                $styles = "width:100%;";
                $size = "100%";
            } elseif (in_array('photo', $cardType_arr)) {

                $styles = "width:100%;";
                $size = "100%";

            } elseif (in_array('player', $cardType_arr)) {

                $styles = "width:100%;";
                $img = ($is_og == 'yes') ? $img_arr['image'] : $img_arr['image:src'];
                $src = "controls poster";
                $tag = "video";
                $close_tag = "</video>";
                $size = "100%";

            } elseif (in_array('gallery', $cardType_arr)) {

                $hide = 'hide';
                $gallery_meta = '<div class="gallery-meta-container">';

                if (is_array($img_arr)) {

                    $i = 0;

                    foreach ($img_arr as $name => $url) $gallery_meta .= '<img class="tile" src="' . $url . '" alt="" />';

                    $i++;

                    if ($i > 3) {

                        break;
                    }

                }

                $gallery_meta .= '</div>';

            } elseif (in_array('summary', $cardType_arr)) {

                $styles = 'width: 60px; height: 60px; margin-left:.6em;';
                $size = 60;
                $hide = 'hide';
                $class = 'summary-image';
                $img_summary = '<img class="' . $class . '" width="' . $size . '" height="' . $size . '" style="' . $styles . ' -webkit-user-drag: none; " ' . $src . '="' . $img . '">';
                $float = 'float:right;';

            } elseif (in_array('product', $cardType_arr)) {

                $product_meta = '<div class="product-view" style="position:relative;">';
                $product_meta .= '<span class="bigger"><strong>' . $product_arr['data1'] . '</strong></span>';
                $product_meta .= '<span>' . $product_arr['label1'] . '</span>';
                $product_meta .= '<span class="bigger"><strong>' . $product_arr['data2'] . '</strong></span>';
                $product_meta .= '<span>' . $product_arr['label2'] . '</span>';
                $product_meta .= '</div>';

                $styles = 'float:left; width: 120px; height: 120px; margin-right:.6em;';
                $size = 120;
            } elseif (in_array('app', $cardType_arr)) {

                $hide = 'hide';
                $class = 'bg-opacity';
                $app = '<div class="app-view" style="float:left;">';
                $app .= '<strong>' . __('Preview for app cards is not available yet.', 'jm-tc') . '</strong>';
                $app .= '</div>';
            } else {

                $styles = "float:none;";
            }


            $output = '<div class="fake-twt">';
            $output .= $app;
            $output .= '<div class="e-content ' . $class . '">
							<div style="float:left;">
							' . get_avatar(false, 16) . '
							
							<span>' . __('Name associated with ', 'jm-tc') . $site_arr['site'] . '</span>
							
							<div style="float:left;" class="' . $hide . '">
								<' . $tag . ' class="' . $class . '" width="' . $size . '" height="' . $size . '" style="' . $styles . ' -webkit-user-drag: none; " ' . $src . '="' . $img . '">' . $close_tag . '
							
							' . $product_meta . '
							</div>
							</div>
							
							' . $gallery_meta . '
									
							<div style="float:left;">
							<div><strong>' . $title_arr['title'] . '</strong></div>
							<div><em>By ' . __('Name associated with ', 'jm-tc') . $creator_arr['creator'] . '</em></div>
							<div>' . $description_arr['description'] . '</div>
							</div>
							'
                . $img_summary .
                '
							
							<div style="float:left;" class="gray"><strong>' . __('View on the web', 'jm-tc') . '<strong></div>
						
						</div></div>';

            $output .= '</div>';

            return $output;

        }
    }
}