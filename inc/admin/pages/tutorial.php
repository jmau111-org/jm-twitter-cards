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
	$urls =  array(
		__('Start', 'jm-tc') 							=> '8l4k3zrD4Z0',
		__('Troubleshooting', 'jm-tc')				 	=> 'sNihgEu65L0',
		__('Multi-author', 'jm-tc')				 		=> 'LpQuIzaHqtk',
		__('Preview', 'jm-tc')				 			=> '',
	);
	
	foreach ( $urls as $title => $id ) :
	 echo '<h3>'.$title.'</h3>'. '<p>' . wp_oembed_get( esc_url('http://www.youtube.com/watch?v='.$id ), array( 'width' => 800 ) ). '</p><br/>';
	endforeach;
?>
</div>