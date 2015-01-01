<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (class_exists('JM_TC_Utilities')) {

    class JM_TC_Markup{
        /**
         * Options
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
         * Add just one line before meta
         * @since 5.3.2
         * @param bool $end
         * @return string
         */
        public function html_comments($end = false){

            if (!$end)
                echo "\n" . '<!-- JM Twitter Cards by Julien Maury ' . JM_TC_VERSION . ' -->' . "\n";
            else
                echo '<!-- /JM Twitter Cards ' . JM_TC_VERSION . ' -->' . "\n\n";
        }


        /*
         * Add meta to head section
         * @since 5.3.2
         */
        public function add_markup(){

            global $jm_twitter_cards;
            $jm_twitter_cards['options'] = new JM_TC_Options;
            $options = $jm_twitter_cards['options'];

            if (
                is_singular()
                && !is_front_page()
                && !is_home()
                && !is_404()
                && !is_tag()

            ) {

                // safer than the global $post => seems killed on a lot of install :/
                $post_ID = get_queried_object()->ID;

                $this->html_comments();

                /* most important meta */
                $this->display_markup($options->cardType($post_ID));
                $this->display_markup($options->creatorUsername(true));
                $this->display_markup($options->siteUsername());
                $this->display_markup($options->title($post_ID));
                $this->display_markup($options->description($post_ID));
                $this->display_markup($options->image($post_ID));


                /* secondary meta */
                $this->display_markup($options->cardDim($post_ID));
                $this->display_markup($options->product($post_ID));
                $this->display_markup($options->player($post_ID));
                $this->display_markup($options->deeplinking());

                $this->html_comments(true);


            }

            if (is_home() || is_front_page()) {

                $this->html_comments();

                $this->display_markup($options->cardType());
                $this->display_markup($options->siteUsername());
                $this->display_markup($options->creatorUsername());
                $this->display_markup($options->title());
                $this->display_markup($options->description());
                $this->display_markup($options->image());
                $this->display_markup($options->cardDim());
                $this->display_markup($options->deeplinking());

                $this->html_comments(true);
            }

        }

        /*
        *   Display the different meta
        *	@since 5.3.2
        *   @param mixed $data
        *   @return string
        */
        protected function display_markup($data){

            if (is_array($data)) {

                foreach ($data as $name => $value) {

                    if ($value != '') {

                        if ($this->opts['twitterCardOg'] == 'yes' && in_array($name, array('title', 'description', 'image', 'image:width', 'image:height'))) {

                            $is_og = 'og';
                            $name_tag = 'property';

                        } else {

                            $is_og = 'twitter';
                            $name_tag = 'name';
                        }

                        echo $meta = '<meta ' . $name_tag . '="' . $is_og . ':' . $name . '" content="' . $value . '">' . "\n";

                    }

                }

            } elseif (is_string($data)) {

                echo $meta = '<!-- [(-_-)@ ' . $data . ' @(-_-)] -->' . "\n";

            }

        }


    }

}
