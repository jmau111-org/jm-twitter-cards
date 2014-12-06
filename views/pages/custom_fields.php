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
     * Fields for admin page custom fields
     * @return array
     */
    function jm_tc_seo_options()
    {
        $plugin_options = array(
            'id' => 'jm_tc',
            'show_on' => array('key' => 'options-page', 'value' => array('jm_tc_cf',),),
            'show_names' => true,
            'fields' => array(

                array(
                    'name' => __('Custom field title', JM_TC_TEXTDOMAIN),
                    'desc' => __('If you prefer to use your own field for twitter meta title instead of SEO plugin. Leave it blank if you want to use SEO plugin or default title.', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardTitle',
                    'type' => 'text_medium',
                ),

                array(
                    'name' => __('Custom field desc', JM_TC_TEXTDOMAIN),
                    'desc' => __('If you prefer to use your own field for twitter meta description instead of SEO plugin. Leave it blank if you want to use SEO plugin or default desc.', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardDesc',
                    'type' => 'text_medium',
                ),

            )
        );

        return $plugin_options;
    }

    ?>

    <?php cmb_metabox_form(jm_tc_seo_options(), JM_TC_Admin::key()); ?>
    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(3); ?>
    </div>

</div>


