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
     * Fields for admin page meta box
     * @return array
     */
    function jm_tc_meta_box_options()
    {
        $plugin_options = array(
            'id' => 'jm_tc',
            'show_on' => array('key' => 'options-page', 'value' => array('jm_tc_meta_box',),),
            'show_names' => true,
            'fields' => array(

                array(
                    'name' => __('Add or hide the meta box', JM_TC_TEXTDOMAIN),
                    'desc' => __('Hide or display the meta box on post edit. This will display/hide both image and main metaboxes.', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardMetabox',
                    'type' => 'select',
                    'options' => array(
                        'yes' => __('Display', JM_TC_TEXTDOMAIN),
                        'no' => __('Hide', JM_TC_TEXTDOMAIN),

                    )
                ),

            )
        );

        return $plugin_options;
    }

    ?>
    <?php cmb_metabox_form(jm_tc_meta_box_options(), JM_TC_Admin::key()); ?>

    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(2); ?>
    </div>
</div>


