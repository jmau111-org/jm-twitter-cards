jm_tc_<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php echo JM_TC_Tabs::admin_tabs();?>

	

	<?php
	
	function jm_tc_image_options() {
	
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_images', ), ),
		'show_names' => true,
		'fields'     => array(
		
		array(
			'name' => __( 'Image Fallback', 'jm-tc' ),
			'id'   => 'twitterImage', // Not used but needed for plugin
			'type' => 'file',
			
		),
		

		array(
		'name' 		=> __('Image width', 'jm-tc'),
		'desc' 		=> __('px', 'jm-tc'),
		'id'   		=> 'twitterImageWidth',
		'type' 		=> 'text_number',
		'min'		=> 280,
		'max'  		=> PHP_INT_MAX,
		),	

		array(
		'name' 		=> __('Image height', 'jm-tc'),
		'desc' 		=> __('px', 'jm-tc'),
		'id'   		=> 'twitterImageHeight',
		'type' 		=> 'text_number',
		'min'		=> 150,
		'max'  		=> PHP_INT_MAX,
		),	
		
		
		array(
		'name' 		=> __( 'Crop', 'jm-tc' ),
		'desc' 		=> __('Do you want to force crop on card Image?', 'jm-tc'),
		'id'   		=> 'twitterCardCrop',
		'type' 		=> 'select',
		'options'	 => array(
		'no' 			=> __( 'No', 'jm-tc' ),
		'yes' 			=> __( 'Yes', 'jm-tc' ),
		)
		),
		
		array(
		'name'     => __( 'Define specific size for twitter:image display', 'jm-tc'),
		'id'       => 'twitterCardImgSize',
		'type'     => 'select',

		'options'  => array(
		'small'			    => __('280 x 375 px', 'jm-tc'),
		'web'   			=> __('560 x 750 px', 'jm-tc'),
		'mobile-non-retina' => __('435 x 375 px', 'jm-tc'),
		'mobile-retina'  	=> __('280 x 150 px', 'jm-tc'),
		)

		),
		
		)
		);
		
		return $plugin_options;
	}
	?>
	<?php cmb_metabox_form( jm_tc_image_options(), JM_TC_Admin::key() ); ?>
	
		<div class="doc-valid">
			<?php echo JM_TC_Admin::docu_links(4);?>
		</div>

</div>


