<?php

namespace TokenToMe\TwitterCards\Admin;

if (!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

$cardTypeGeneral = (!empty($this->opts['twitterCardType'])) ? $this->opts['twitterCardType'] : '';

$metaBox = [
	['method' => 'wrapper', 'tag' => 'table', 'class' => 'form-table', 'mod' => 'start'],
	['method' => 'wrapper', 'tag' => 'tbody', 'mod' => 'start'],
	[
		'method'   => 'select_field',
		'label'    => esc_html__('Card type', 'jm-tc'),
		'field_id' => 'twitterCardType',
		'options'  => [
			'summary'             => esc_html__('Summary', 'jm-tc'),
			'summary_large_image' => esc_html__('Summary below Large Image', 'jm-tc'),
			'player'              => esc_html__('Player', 'jm-tc'),
			'app'                 => esc_html__('Application', 'jm-tc'),
		],
		'type'     => 'select_field',
		'value'    => (get_post_meta(self::get_post_id(), 'twitterCardType', true))
			? get_post_meta(self::get_post_id(), 'twitterCardType', true)
			: $cardTypeGeneral,
	],
	[
		'method'   => 'image_field',
		'field_id' => 'cardImage',
		'label'    => esc_html__('Set another source as twitter image (enter URL)', 'jm-tc'),
		'value'    => get_post_meta(self::get_post_id(), 'cardImage', true),
	],
	[
		'method'    => 'textarea_field',
		'field_id'  => 'cardImageAlt',
		'label'     => esc_html__('Image Alt', 'jm-tc'),
		'value'     => get_post_meta(self::get_post_id(), 'cardImageAlt', true),
		'charcount' => 420,
	],
	[
		'method'   => 'url_field',
		'field_id' => 'cardPlayer',
		'label'    => esc_html__('URL of iFrame player (MUST BE HTTPS)', 'jm-tc'),
		'value'    => get_post_meta(self::get_post_id(), 'cardPlayer', true),
	],
	[
		'method'   => 'num_field',
		'field_id' => 'cardPlayerWidth',
		'label'    => esc_html__('Player width', 'jm-tc'),
		'min'      => 262,
		'max'      => 1000,
		'step'     => 1,
		'value'    => get_post_meta(self::get_post_id(), 'cardPlayerWidth', true),
	],
	[
		'method'   => 'num_field',
		'field_id' => 'cardPlayerHeight',
		'label'    => esc_html__('Player height', 'jm-tc'),
		'type'     => 'number',
		'min'      => 196,
		'max'      => 1000,
		'step'     => 1,
		'value'    => get_post_meta(self::get_post_id(), 'cardPlayerHeight', true),
	],
	['method' => 'wrapper', 'tag' => 'tbody', 'mod' => 'end'],
	['method' => 'wrapper', 'tag' => 'table', 'mod' => 'end'],
];
