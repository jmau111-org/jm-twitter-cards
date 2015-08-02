<?php
namespace TokenToMe\TwitterCards\Admin;

if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
	<h1 class="page-title-action">JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php echo Tabs::admin_tabs(); ?>

	<?php
	/**
	 * Fields for admin page multi-options
	 * @return array
	 */
	function jm_tc_multi_author_options() {
		$plugin_options = array(
			'id'         => 'jm_tc',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( 'jm_tc_multi_author', ), ),
			'show_names' => true,
			'fields'     => array(

				array(
					'name'    => __( 'Add a field in profiles for author', JM_TC_TEXTDOMAIN ),
					'desc'    => __( 'This would add a field in profiles if user can publish posts. In this case his Twitter username will be set as meta creator.', JM_TC_TEXTDOMAIN ),
					'id'      => 'twitterProfile',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'No', JM_TC_TEXTDOMAIN ),
						'yes' => __( 'Yes', JM_TC_TEXTDOMAIN ),

					)
				),
				array(
					'name' => __( 'Meta key Twitter', JM_TC_TEXTDOMAIN ),
					'desc' => __( 'If the above option is set to "no", just modify user meta key associated with Twitter Account in profiles to get Twitter usernames from your own fields:', JM_TC_TEXTDOMAIN ),
					'id'   => 'twitterUsernameKey',
					'type' => 'text_medium'
				),

			)
		);

		return $plugin_options;

	}

	cmb_metabox_form( jm_tc_multi_author_options(), Main::key() ); ?>
</div>


