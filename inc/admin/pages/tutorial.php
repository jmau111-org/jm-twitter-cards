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
	$urls =  array(
		__('How to set it the first time', 'jm-tc') => '8l4k3zrD4Z0',
	);
	
	foreach ( $urls as $title => $id ) :
	 echo '<h3>'.$title.'</h3>'. '<p>' . wp_oembed_get( esc_url('http://www.youtube.com/watch?v='.$id ) ). '</p><br/>';
	endforeach;
?>
</div>