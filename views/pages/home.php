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
     * Fields for admin page home (page for posts/front page)
     * @return array
     */
    function jm_tc_home_options()
    {
        $plugin_options = array(
            'id' => 'jm_tc',
            'show_on' => array('key' => 'options-page', 'value' => array('jm_tc_home',),),
            'show_names' => true,
            'fields' => array(

                array(
                    'name' => __('Home meta desc', JM_TC_TEXTDOMAIN),
                    'desc' => __('Enter desc for Posts Page (max: 200 characters)', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterPostPageDesc',
                    'type' => 'textarea_small',
                ),

            )
        );

        return $plugin_options;
    }

    ?>
    <?php cmb_metabox_form(jm_tc_home_options(), JM_TC_Admin::key()); ?>

    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(2); ?>
    </div>
</div>


