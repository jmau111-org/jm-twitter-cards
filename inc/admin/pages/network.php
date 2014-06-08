<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php _e('Network', 'jm-tc'); ?></h2>
<?php
	function jm_tc_network_options() {
		
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_network', ), ),
		'show_names' => true,
		'fields'     => array(

			array(
			'name' 		=> __( 'Site (twitter username)', 'jm-tc' ),
			'desc' 		=> __('Who is the Owner of the Website? (could be a trademark)',  'jm-tc'),
			'id'   		=> 'twitterNetworkSite',
			'type' 		=> 'text_medium',
			),				

			
			array(
			'name' 		=> __( 'Open Graph', 'jm-tc' ),
			'desc' 		=> __( 'Open Graph/SEO', 'jm-tc'),
			'id'   		=> 'twitterNetworkCardOg',
			'type' 		=> 'select',
			'options' 	=> array(
			'no' 		=> __( 'no', 'jm-tc' ),
			'yes' 		=> __( 'yes', 'jm-tc' ),
			)
			),
	
			)
		);
			
		return $plugin_options;
	}
	
	//$debug = new JM_TC_Options;
	//$debug->showVisible('JM_TC_Options');
	
	cmb_metabox_form( jm_tc_network_options(), JM_TC_Admin::key() ); ?>
</div>


