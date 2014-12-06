<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

?>
<div class="wrap">
    <h2>JM Twitter Cards : <?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php echo JM_TC_Tabs::admin_tabs(); ?>

    <?php
    /**
     * Fields for admin page deep linking
     * @return array
     */
    function jm_tc_deep_linking_options()
    {

        $plugin_options = array(
            'id' => 'jm_tc',
            'show_on' => array('key' => 'options-page', 'value' => array('jm_tc_deep_linking',),),
            'show_names' => true,
            'fields' => array(


                array(
                    'name' => __('Deep linking? ', JM_TC_TEXTDOMAIN),
                    'desc' => __('For all the following fields, if you do not want to use leave it blank but be careful with the required markup for your app. Read the documentation please.', JM_TC_TEXTDOMAIN),
                    'id' => 'deep_linking_title',
                    'type' => 'title',
                ),

                array(
                    'name' => __('iPhone Name', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPhone Name ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPhoneName',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __(' iPhone URL', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPhone URL ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPhoneUrl',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('iPhone ID', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPhone ID ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPhoneId',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('iPad Name', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPad Name ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPadName',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('iPad URL', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPad URL ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPadUrl',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('iPad ID', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter iPad ID ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitteriPadId',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('Google Play Name', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter Google Play Name ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterGooglePlayName',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('Google Play URL', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter Google Play URL ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterGooglePlayUrl',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('Google Play ID', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter Google Play ID ', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterGooglePlayId',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('App Country code', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter 2 letter App Country code in case your app is not available in the US app store', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterAppCountry',
                    'type' => 'text_medium',
                ),
            )
        );

        return $plugin_options;
    }

    ?>
    <?php cmb_metabox_form(jm_tc_deep_linking_options(), JM_TC_Admin::key()); ?>

    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(5); ?>
    </div>
</div>


