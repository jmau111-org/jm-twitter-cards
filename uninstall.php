<?php

// If cheating exit
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option(JM_TC_SLUG_MAIN_OPTION);
delete_option(JM_TC_SLUG_CPT_OPTION);

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
foreach ($keys as $key) {
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
