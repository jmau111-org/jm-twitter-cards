<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php echo JM_TC_Tabs::admin_tabs();?>

<?php
$author = new JM_TC_Author;

//plugin list
$slugs = array(
			'jm-dashicons-shortcode' 			=> 'JM Dashicons Shortcode',
			'jm-last-twit-shortcode' 			=> 'JM Last Twit Shortcode',
			'jm-twit-this-comment'				=> 'JM Twit This Comment',
			'jm-simple-qr-code-widget' 			=> 'JM Simple QR Code Widget',
			'jm-wp-d-vine' 						=> 'JM WP D-Vine',
			'jm-instagram-feed-widget'			=> 'JM Instagram Feed Widget'
			);

$author->get_author_infos(
	'Julien Maury', 
	__('I am a WordPress Developer, I like to make it simple.', 'jm-tc'), 
	'tweetpressfr@gmail.com', 
	'http://tweetpressfr.github.io', 
	'7BJYYT486HEH6', 
	'tweetpressfr',
	'https://plus.google.com/u/0/+JulienMaury',  
	$slugs
	);
?>
</div>


