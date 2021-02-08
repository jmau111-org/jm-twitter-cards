<?php

namespace TokenToMe\TwitterCards\Admin;

if (!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

$opts = \jm_tc_get_options();
$settings_fields = [
	JM_TC_SLUG_MAIN_OPTION => [
		[
			'name'  => 'twitterCreator',
			'label' => esc_html__('Creator (twitter username)', 'jm-tc'),
			'type'  => 'text',
		],
		[
			'name'  => 'twitterSite',
			'label' => esc_html__('Site (twitter username)', 'jm-tc'),
			'type'  => 'text',
		],
		[
			'name'    => 'twitterCardType',
			'label'   => esc_html__('Card type', 'jm-tc'),
			'type'    => 'select',
			'default' => 'summary',
			'options' => [
				'summary'             => esc_html__('Summary', 'jm-tc'),
				'summary_large_image' => esc_html__('Summary below Large Image', 'jm-tc'),
				'app'                 => esc_html__('Application', 'jm-tc'),
			],
		],
		[
			'name'    => 'twitterCardExcerpt',
			'label'   => esc_html__('Excerpt', 'jm-tc'),
			'desc'    => esc_html__('Excerpt as meta desc?', 'jm-tc'),
			'type'    => 'radio',
			'default' => 'no',
			'options' => [
				'yes' => esc_html__('yes', 'jm-tc'),
				'no'  => esc_html__('no', 'jm-tc'),
			],
		],
		[
			'name'    => 'twitterCardOg',
			'label'   => esc_html__('Open Graph', 'jm-tc'),
			'desc'    => esc_html__('Open Graph/SEO', 'jm-tc'),
			'type'    => 'radio',
			'default' => 'no',
			'options' => [
				'no'  => esc_html__('no', 'jm-tc'),
				'yes' => esc_html__('yes', 'jm-tc'),
			],
		],
		[
			'name'    => 'twitterImage',
			'label'   => esc_html__('Image Fallback', 'jm-tc'),
			'type'    => 'file',
			'default' => $opts["twitterImage"] ?? "",
		],
		[
			'name'      => 'twitterImageAlt',
			'label'     => esc_html__('Image alt', 'jm-tc'),
			'type'      => 'textarea',
			'charcount' => 420,
		],
		[
			'label'     => esc_html__('Home meta desc', 'jm-tc'),
			'desc'      => esc_html__('Enter desc for Posts Page (max: 200 characters)', 'jm-tc'),
			'name'      => 'twitterPostPageDesc',
			'type'      => 'textarea',
			'charcount' => 200,
		],
		[
			'label' => esc_html__('iPhone Name', 'jm-tc'),
			'desc'  => esc_html__('Enter iPhone Name ', 'jm-tc'),
			'name'  => 'twitteriPhoneName',
			'type'  => 'text',
		],
		[
			'label' => esc_html__(' iPhone URL', 'jm-tc'),
			'desc'  => esc_html__('Enter iPhone URL ', 'jm-tc'),
			'name'  => 'twitteriPhoneUrl',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('iPhone ID', 'jm-tc'),
			'desc'  => esc_html__('Enter iPhone ID ', 'jm-tc'),
			'name'  => 'twitteriPhoneId',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('iPad Name', 'jm-tc'),
			'desc'  => esc_html__('Enter iPad Name ', 'jm-tc'),
			'name'  => 'twitteriPadName',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('iPad URL', 'jm-tc'),
			'desc'  => esc_html__('Enter iPad URL ', 'jm-tc'),
			'name'  => 'twitteriPadUrl',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('iPad ID', 'jm-tc'),
			'desc'  => esc_html__('Enter iPad ID ', 'jm-tc'),
			'name'  => 'twitteriPadId',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('Google Play Name', 'jm-tc'),
			'desc'  => esc_html__('Enter Google Play Name ', 'jm-tc'),
			'name'  => 'twitterGooglePlayName',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('Google Play URL', 'jm-tc'),
			'desc'  => esc_html__('Enter Google Play URL ', 'jm-tc'),
			'name'  => 'twitterGooglePlayUrl',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('Google Play ID', 'jm-tc'),
			'desc'  => esc_html__('Enter Google Play ID ', 'jm-tc'),
			'name'  => 'twitterGooglePlayId',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('App Country code', 'jm-tc'),
			'desc'  => esc_html__('Enter 2 letter App Country code in case your app is not available in the US app store', 'jm-tc'),
			'name'  => 'twitterAppCountry',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('Custom field title', 'jm-tc'),
			'desc'  => esc_html__('If you prefer to use your own field for twitter meta title instead of SEO plugin. Leave it blank if you want to use SEO plugin or default title.', 'jm-tc'),
			'name'  => 'twitterCardTitle',
			'type'  => 'text',
		],
		[
			'label' => esc_html__('Custom field desc', 'jm-tc'),
			'desc'  => esc_html__('If you prefer to use your own field for twitter meta description instead of SEO plugin. Leave it blank if you want to use SEO plugin or default desc.', 'jm-tc'),
			'name'  => 'twitterCardDesc',
			'type'  => 'text',
		],
	],
	JM_TC_SLUG_CPT_OPTION  => [
		[
			'name'    => 'twitterCardPt',
			'label'   => esc_html__('Add or hide the meta box', 'jm-tc'),
			'desc'    => esc_html__('Default', 'jm-tc') . ': ' . esc_html__('All', 'jm-tc'),
			'type'    => 'multicheck',
			'options' => $this->get_post_types(),
		],
	],
];
