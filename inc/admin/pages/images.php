<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php $admin = new JM_TC_Admin(); ?>

	<?php
	
	function image_options() {
	
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_images', ), ),
		'show_names' => true,
		'fields'     => array(
		
		array(
			'name' => __( 'Image Fallback', 'jm-tc' ),
			'id'   => 'twitterCardImage', // Not used but needed for plugin
			'type' => 'file',
			
		),
		

		array(
		'name' 		=> __('Image width', 'jm-tc'),
		'desc' 		=> __('px', 'jm-tc'),
		'id'   		=> 'twitterCardImageWidth',
		'type' 		=> 'text_number',
		'min'		=> 280,
		'max'  		=> PHP_INT_MAX,
		),	

		array(
		'name' 		=> __('Image width', 'jm-tc'),
		'desc' 		=> __('px', 'jm-tc'),
		'id'   		=> 'twitterCardImageHeight',
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
		
		)
		);
		
		return $plugin_options;
	}
	?>
	<?php cmb_metabox_form( image_options(), $admin->key() ); ?>
	
		<div class="doc-valid">
	<?php echo $admin->docu_links(4);?>
	</div>

</div>


