<?php

// If cheating exit
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


delete_option( 'jm_tc' );
delete_option( 'jm_tc_cpt' );


/**
 * Delete postmeta from option table
 *
 */
$keys = [
	'twitterCardType',
	'cardDesc',
	'cardTitle',
	'cardImageID',
	'cardImage',
	'cardImageAlt',
	'cardPlayer',
	'cardImageWidth',
	'cardImageHeight',
	'cardPlayerWidth',
	'cardPlayerHeight',
	'cardPlayerStream',
	'cardPlayerCodec',
	'cardData1',
	'cardLabel1',
	'cardData2',
	'cardLabel2',
];

global $wpdb;
foreach ( $keys as $key ) {
	$wpdb->query(
		$wpdb->prepare(
			"
			 DELETE FROM $wpdb->postmeta
			 WHERE meta_key = %s
			",
			$key
		)
	);
}