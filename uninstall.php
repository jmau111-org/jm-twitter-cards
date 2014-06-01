<?php

// If cheating exit
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();


function jm_tc_on_delete() {
	
	delete_option( 'jm_tc');  

	/**
	 * Delete postmeta from option table
	 *
	 */
	$keys = array(
				'twitterCardType', 
				'cardImage',
				'cardPlayer',
				'cardImageWidth',
				'cardImageHeight',
				'cardPlayerWidth',
				'cardPlayerHeight',
				'cardPlayerStream',
				'cardData1',
				'cardLabel1',
				'cardData2',
				'cardLabel2',
				'cardImgSize',
			);
			
			
	foreach($keys as $key)	{
	global $wpdb;
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
}

if( !is_multisite() ) {

	jm_tc_delete();

} else {
    // For regular options.
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    foreach ( $blog_ids as $blog_id ) 
    {
        switch_to_blog( $blog_id );
        jm_tc_delete();  
		restore_current_blog();
    }
	
}