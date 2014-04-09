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
	function deep_linking_options() {
	
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_deep_linking', ), ),
		'show_names' => true,
		'fields'     => array(

			
			array(
			'name' 		=> __( 'Deep linking? ', 'jm-tc'),
			'desc' 		=> __('For all the following fields, if you do not want to use leave it blank but be careful with the required markup for your app. Read the documentation please.','jm-tc'),
			'id'   		=> 'deep_linking_title',
			'type' 		=> 'title',
			),

			array(
			'name' 		=> __( 'iPhone Name', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone Name ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( ' iPhone URL', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone URL ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPhone ID', 'jm-tc' ),
			'desc' 		=> __('Enter iPhone ID ', 'jm-tc'),
			'id'   		=> 'twitterCardiPhoneId',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad Name', 'jm-tc' ),
			'desc' 		=> __('Enter iPad Name ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad URL', 'jm-tc' ),
			'desc' 		=> __('Enter iPad URL ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'iPad ID', 'jm-tc' ),
			'desc' 		=> __('Enter iPad ID ', 'jm-tc'),
			'id'   		=> 'twitterCardiPadId',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play Name', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play Name ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayName',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play URL', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play URL ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayUrl',
			'type' 		=> 'text_medium',
			),
			
			array(
			'name' 		=> __( 'Google Play ID', 'jm-tc' ),
			'desc' 		=> __('Enter Google Play ID ', 'jm-tc'),
			'id'   		=> 'twitterCardGooglePlayId',
			'type' 		=> 'text_medium',
			),
			
		)
		);
		
		return $plugin_options;
	}
	
	?>
	<?php cmb_metabox_form( deep_linking_options(), $admin->key() ); ?>
	
	<div class="doc-valid">
	<?php echo $admin->docu_links(5); ?>
	</div>
</div>


