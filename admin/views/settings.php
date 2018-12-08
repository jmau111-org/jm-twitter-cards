<?php

namespace TokenToMe\TwitterCards\Admin;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$settings_fields = [
	JM_TC_SLUG_MAIN_OPTION => [
		[
			'name'  => 'twitterCreator',
			'label' => __( 'Creator (twitter username)', 'jm-tc' ),
			'type'  => 'text',
		],
		[
			'name'  => 'twitterSite',
			'label' => __( 'Site (twitter username)', 'jm-tc' ),
			'type'  => 'text',
		],
		[
			'name'    => 'twitterCardType',
			'label'   => __( 'Card type', 'jm-tc' ),
			'type'    => 'select',
			'default' => 'summary',
			'options' => [
				'summary'             => __( 'Summary', 'jm-tc' ),
				'summary_large_image' => __( 'Summary below Large Image', 'jm-tc' ),
				'app'                 => __( 'Application', 'jm-tc' ),
			],
		],
		[
			'name'    => 'twitterCardExcerpt',
			'label'   => __( 'Excerpt', 'jm-tc' ),
			'desc'    => __( 'Excerpt as meta desc?', 'jm-tc' ),
			'type'    => 'radio',
			'default' => 'no',
			'options' => [
				'yes' => __( 'yes', 'jm-tc' ),
				'no'  => __( 'no', 'jm-tc' ),
			],
		],
		[
			'name'    => 'twitterCardOg',
			'label'   => __( 'Open Graph', 'jm-tc' ),
			'desc'    => __( 'Open Graph/SEO', 'jm-tc' ),
			'type'    => 'radio',
			'default' => 'no',
			'options' => [
				'no'  => __( 'no', 'jm-tc' ),
				'yes' => __( 'yes', 'jm-tc' ),
			],
		],
		[
			'name'    => 'twitterImage',
			'label'   => __( 'Image Fallback', 'jm-tc' ),
			'type'    => 'file',
			'default' => '',
		],
		[
			'name'      => 'twitterImageAlt',
			'label'     => __( 'Image alt', 'jm-tc' ),
			'type'      => 'textarea',
			'charcount' => 420,
		],
		[
			'label'     => __( 'Home meta desc', 'jm-tc' ),
			'desc'      => __( 'Enter desc for Posts Page (max: 200 characters)', 'jm-tc' ),
			'name'      => 'twitterPostPageDesc',
			'type'      => 'textarea',
			'charcount' => 200,
		],
		[
			'label' => __( 'iPhone Name', 'jm-tc' ),
			'desc'  => __( 'Enter iPhone Name ', 'jm-tc' ),
			'name'  => 'twitteriPhoneName',
			'type'  => 'text',
		],
		[
			'label' => __( ' iPhone URL', 'jm-tc' ),
			'desc'  => __( 'Enter iPhone URL ', 'jm-tc' ),
			'name'  => 'twitteriPhoneUrl',
			'type'  => 'text',
		],
		[
			'label' => __( 'iPhone ID', 'jm-tc' ),
			'desc'  => __( 'Enter iPhone ID ', 'jm-tc' ),
			'name'  => 'twitteriPhoneId',
			'type'  => 'text',
		],
		[
			'label' => __( 'iPad Name', 'jm-tc' ),
			'desc'  => __( 'Enter iPad Name ', 'jm-tc' ),
			'name'  => 'twitteriPadName',
			'type'  => 'text',
		],
		[
			'label' => __( 'iPad URL', 'jm-tc' ),
			'desc'  => __( 'Enter iPad URL ', 'jm-tc' ),
			'name'  => 'twitteriPadUrl',
			'type'  => 'text',
		],
		[
			'label' => __( 'iPad ID', 'jm-tc' ),
			'desc'  => __( 'Enter iPad ID ', 'jm-tc' ),
			'name'  => 'twitteriPadId',
			'type'  => 'text',
		],
		[
			'label' => __( 'Google Play Name', 'jm-tc' ),
			'desc'  => __( 'Enter Google Play Name ', 'jm-tc' ),
			'name'  => 'twitterGooglePlayName',
			'type'  => 'text',
		],
		[
			'label' => __( 'Google Play URL', 'jm-tc' ),
			'desc'  => __( 'Enter Google Play URL ', 'jm-tc' ),
			'name'  => 'twitterGooglePlayUrl',
			'type'  => 'text',
		],
		[
			'label' => __( 'Google Play ID', 'jm-tc' ),
			'desc'  => __( 'Enter Google Play ID ', 'jm-tc' ),
			'name'  => 'twitterGooglePlayId',
			'type'  => 'text',
		],
		[
			'label' => __( 'App Country code', 'jm-tc' ),
			'desc'  => __( 'Enter 2 letter App Country code in case your app is not available in the US app store', 'jm-tc' ),
			'name'  => 'twitterAppCountry',
			'type'  => 'text',
		],
		[
			'label' => __( 'Custom field title', 'jm-tc' ),
			'desc'  => __( 'If you prefer to use your own field for twitter meta title instead of SEO plugin. Leave it blank if you want to use SEO plugin or default title.', 'jm-tc' ),
			'name'  => 'twitterCardTitle',
			'type'  => 'text',
		],
		[
			'label' => __( 'Custom field desc', 'jm-tc' ),
			'desc'  => __( 'If you prefer to use your own field for twitter meta description instead of SEO plugin. Leave it blank if you want to use SEO plugin or default desc.', 'jm-tc' ),
			'name'  => 'twitterCardDesc',
			'type'  => 'text',
		],
	],
	JM_TC_SLUG_CPT_OPTION  => [
		[
			'name'    => 'twitterCardPt',
			'label'   => __( 'Add or hide the meta box', 'jm-tc' ),
			'desc'    => __( 'Default', 'jm-tc' ) . ': ' . __( 'All', 'jm-tc' ),
			'type'    => 'multicheck',
			'options' => $this->get_post_types(),
		],
	],
];
