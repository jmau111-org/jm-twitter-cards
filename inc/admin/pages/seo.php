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
	<?php echo $admin->docu_links(3);?>
	</div>
		
		
	<?php
	
	function seo_options() {
	
		$plugin_options = array(
		'id'         => 'jm_tc',
		'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_seo', ), ),
		'show_names' => true,
		'fields'     => array(
			
			array(
			'name' 		=> __( 'Meta title', 'jm-tc' ),
			'desc' 		=> __('Use SEO by Yoast or All in ONE SEO meta title for your cards (<strong>default is yes</strong>)', 'jm-tc'),
			'id'   		=> 'twitterCardSEOTitle',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),
			
			array(
			'name' 		=> __( 'Meta Desc', 'jm-tc' ),
			'desc' 		=> __('Use SEO by Yoast or All in ONE SEO meta description for your cards (<strong>default is yes</strong>)', 'jm-tc'),
			'id'   		=>  'twitterCardSEODesc',
			'type' 		=> 'select',
			'options'	 => array(
			'no' 			=> __( 'No', 'jm-tc' ),
			'yes' 			=> __( 'Yes', 'jm-tc' ),
			)
			),
			//'
			
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
</div>


