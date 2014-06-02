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

	$urls = JM_TC_Utilities::youtube_urls();
	
	foreach ( $urls as $title => $id ) :
	 echo '<h3 id="'.$id.'">'.$title.'</h3>'. '<p>' . wp_oembed_get( esc_url('http://www.youtube.com/watch?v='.$id ), array( 'width' => 800 ) ). '</p><br/>';
	endforeach;
?>
</div>