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
     * Fields for admin page images
     * @return array
     */
    function jm_tc_image_options()
    {

        $plugin_options = array(
            'id' => 'jm_tc',
            'show_on' => array('key' => 'options-page', 'value' => array('jm_tc_images',),),
            'show_names' => true,
            'fields' => array(

                array(
                    'name' => __('Image Fallback', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterImage', // Not used but needed for plugin
                    'type' => 'file',

                ),


                array(
                    'name' => __('Image width', JM_TC_TEXTDOMAIN),
                    'desc' => __('px', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterImageWidth',
                    'type' => 'text_number',
                    'min' => 280,
                    'max' => 1000,
                ),

                array(
                    'name' => __('Image height', JM_TC_TEXTDOMAIN),
                    'desc' => __('px', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterImageHeight',
                    'type' => 'text_number',
                    'min' => 150,
                    'max' => 1000,
                ),


                array(
                    'name' => __('Crop', JM_TC_TEXTDOMAIN),
                    'desc' => __('Do you want to force crop on card Image?', JM_TC_TEXTDOMAIN) . __(' (Super Crop => WordPress 3.9++)', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardCrop',
                    'type' => 'select',
                    'options' => array(
                        'no' => __('No', JM_TC_TEXTDOMAIN),
                        'yes' => __('Yes', JM_TC_TEXTDOMAIN),
                        'yo' => __('Yes', JM_TC_TEXTDOMAIN) . ' (Super Crop)',
                    )
                ),

                array(
                    'name' => __('Crop x', JM_TC_TEXTDOMAIN),
                    'desc' => __(' (Super Crop => WordPress 3.9++)', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardCropX',
                    'type' => 'select',
                    'options' => array(
                        'left' => __('Left', JM_TC_TEXTDOMAIN),
                        'center' => __('Center', JM_TC_TEXTDOMAIN),
                        'right' => __('Right', JM_TC_TEXTDOMAIN),
                    )
                ),

                array(
                    'name' => __('Crop y', JM_TC_TEXTDOMAIN),
                    'desc' => __(' (Super Crop => WordPress 3.9++)', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardCropY',
                    'type' => 'select',
                    'options' => array(
                        'top' => __('Top', JM_TC_TEXTDOMAIN),
                        'center' => __('Center', JM_TC_TEXTDOMAIN),
                        'bottom' => __('Bottom', JM_TC_TEXTDOMAIN),
                    )
                ),


                array(
                    'name' => __('Define specific size for twitter:image display', JM_TC_TEXTDOMAIN),
                    'id' => 'twitterCardImgSize',
                    'type' => 'select',

                    'options' => array(
                        'small' => __('280 x 375 px', JM_TC_TEXTDOMAIN),
                        'web' => __('560 x 750 px', JM_TC_TEXTDOMAIN),
                        'mobile-non-retina' => __('435 x 375 px', JM_TC_TEXTDOMAIN),
                        'mobile-retina' => __('280 x 150 px', JM_TC_TEXTDOMAIN),
                    )

                ),

            )
        );

        return $plugin_options;
    }

    ?>
    <?php cmb_metabox_form(jm_tc_image_options(), JM_TC_Admin::key()); ?>

    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(4); ?>
    </div>

</div>


