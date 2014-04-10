<?php 
if ( ! defined( 'JM_TC_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="wrap">
<h2>JM Twitter Cards : <?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php
$author = new JM_TC_Author();

//plugin list
$slugs = array(
			'jm-dashicons-shortcode' 			=> 'JM Dashicons Shortcode',
			'jm-last-twit-shortcode' 			=> 'JM Last Twit Shortcode',
			'jm-twit-this-comment'				=> 'JM Twit This Comment',
			'jm-simple-qr-code-widget' 			=> 'JM Simple QR Code Widget',
			'jm-html5-and-responsive-gallery' 	=> 'JM HTML5 Responsive Gallery',
			);

$author->get_author_infos('Julien Maury', __('I am a WordPress Developer, I like to make it simple.', 'jm-tc'), 'tweetpressfr@gmail.com', 'http://www.tweetpress.fr', 'http://www.amazon.fr/registry/wishlist/1J90JNIHBBXL8', 'tweetpressfr', $slugs);
?>
</div>


