<?php

namespace TokenToMe\TwitterCards\Admin;

if (!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

$sections = [
	[
		'id'    => JM_TC_SLUG_MAIN_OPTION,
		'title' => esc_html__('Options', 'jm-tc'),
	],
	[
		'id'    => JM_TC_SLUG_CPT_OPTION,
		'title' => esc_html__('Custom Post types', 'jm-tc'),
	],
];
