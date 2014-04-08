<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap" style="max-width:1024px;">
<h2><i class="dashicons dashicons-twitter" style="font-size:2em; margin-right:1em; color:#292F33;"></i> <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php $admin = new JM_TC_Admin(); ?>
<div style="float:right;">
<?php echo $admin->docu_links(0); ?>
</div>
	<?php
	function multi_author_options() {
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_multi_author', ), ),
		'show_names' => true,
		'fields'     => array(				
			
			array(
			'name' 		=> __( 'Meta key Twitter', 'jm-tc' ),
			'desc' 		=> __('Modify user meta key associated with Twitter Account in profiles :', 'jm-tc'),
			'id'   		=> 'twitterCardUsernameKey', 
			'type' 		=> 'text_medium',
			),
			
		)
		);
		
		return $plugin_options;
		
	}
	?>
	<?php cmb_metabox_form( multi_author_options(), $admin->key() ); ?>
</div>


