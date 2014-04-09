<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2><i class="dashicons dashicons-twitter"></i> <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php $admin = new JM_TC_Admin(); ?>
	<div class="floatR">
	<?php echo $admin->docu_links(2); ?>
	</div>
	
	
	<?php
	function home_options() {
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_home', ), ),
		'show_names' => true,
		'fields'     => array(				
			
			array(
			'name' 		=> __( 'Home meta desc', 'jm-tc' ),
			'desc' 		=> __('Enter desc for Posts Page (max: 200 characters)', 'jm-tc'),
			'id'   		=> 'twitterCardPostPageDesc',
			'type' 		=> 'textarea_small',
			),
			
		)
		);
		
		return $plugin_options;
	}
	?>
	<?php cmb_metabox_form( home_options(), $admin->key() ); ?>
</div>


