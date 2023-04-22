<?php

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

WP_CLI::add_command(JM_TC_SLUG_MAIN_OPTION, 'JM_TC_CLI');

class JM_TC_CLI extends WP_CLI_Command
{

    /**
     * Set username
  
     *
     * ## EXAMPLES
     *
     * wp jm_tc set_username rihanna
     *
     * @param $args
     * @param $assoc_args
     */
    public function set_username($args, $assoc_args)
    {

        if (empty($args[0])) {
            WP_CLI::error(__('You sox !', 'jm-tc'));
        }

        $options                   = get_option(JM_TC_SLUG_MAIN_OPTION);
        $options['twitterCreator'] = jm_tc_remove_at($args[0]);
        update_option(JM_TC_SLUG_MAIN_OPTION, $options);

        WP_CLI::success(__('Twitter Cards creator set successfully', 'jm-tc'));
    }

    /**
     * Set sitename
  
     *
     * ## EXAMPLES
     *
     * wp jm_tc set_sitename nbcnews
     *
     * @param $args
     * @param $assoc_args
     */
    public function set_sitename($args, $assoc_args)
    {

        if (empty($args[0])) {
            WP_CLI::error(__('You sox !', 'jm-tc'));
        }

        $options                = get_option(JM_TC_SLUG_MAIN_OPTION);
        $options['twitterSite'] = jm_tc_remove_at($args[0]);
        update_option(JM_TC_SLUG_MAIN_OPTION, $options);

        WP_CLI::success(__('Twitter Cards Sitename set successfully', 'jm-tc'));
    }

    /**
     * Set card type
  
     *
     * ## EXAMPLES
     *
     * wp jm_tc set_sitename nbcnews
     *
     * @param $args
     * @param $assoc_args
     */
    public function set_cardtype($args, $assoc_args)
    {

        if (empty($args[0]) || !in_array($args[0], [
            'summary',
            'summary_large_image',
            'app', # player cards cannot be set globally
        ], true)) {
            WP_CLI::error(__('Error'));
        }

        $options                    = get_option('jm_tc');
        $options['twitterCardType'] = jm_tc_remove_at($args[0]);
        update_option(JM_TC_SLUG_MAIN_OPTION, $options);

        WP_CLI::success(__('Twitter Cards Type set successfully', 'jm-tc'));
    }

    /**
     * Set opengraph
  
     *
     * ## EXAMPLES
     *
     * wp jm_tc set_opengraph yes
     *
     * @param $args
     * @param $assoc_args
     */
    public function set_opengraph($args, $assoc_args)
    {

        if (empty($args[0]) || !in_array($args[0], ['yes', 'no'], true)) {
            WP_CLI::error(__('Error'));
        }

        $options                  = get_option('jm_tc');
        $options['twitterCardOg'] = $args[0];
        update_option(JM_TC_SLUG_MAIN_OPTION, $options);

        WP_CLI::success(__('Twitter Cards creator set successfully', 'jm-tc'));
    }

    /**
     * Set post types for metabox
  
     *
     * ## EXAMPLES
     *
     * wp jm_tc set_post_types post page movie
     *
     * @param $args
     * @param $assoc_args
     */
    public function set_post_types($args, $assoc_args)
    {

        if (empty($args[0])) {
            WP_CLI::error(__('Error'));
        }

        $_pts = [];

        foreach ($args as $arg) {

            if (!post_type_exists($arg)) {
                WP_CLI::error( __('Error') );
                break;
            }


            $_pts['twitterCardPt'][$arg] = $arg;
        }

        update_option(JM_TC_SLUG_CPT_OPTION, $_pts);

        WP_CLI::success(__('Twitter Cards custom post types set successfully', 'jm-tc'));
    }
}
