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
	
	function seo_options() {
	
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_cf', ), ),
		'show_names' => true,
		'fields'     => array(
			
			array(
			'name' 		=> __( 'Custom field title', 'jm-tc' ),
			'desc' 		=> __('If you prefer to use your own fields', 'jm-tc'),
			'id'   		=> 'twitterCardTitle',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Custom field desc', 'jm-tc' ),
			'desc' 		=> __('If you prefer to use your own fields', 'jm-tc'),
			'id'   		=> 'twitterCardDesc',
			'type' 		=> 'text_medium',
			),
	
			)
		);
			
		return $plugin_options;
	}
	?>
	
	<?php cmb_metabox_form( seo_options(), $admin->key() ); ?>
		<div class="doc-valid">
	<?php echo $admin->docu_links(3);?>
	</div>
		
</div>


