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


	
	<figure>
		<img src="https://dev.twitter.com/sites/default/files/images_documentation/card_types.png" alt="" />
		 <figcaption style="float:right;">Source : <a class="button" href="https://analytics.twitter.com/">Twitter Card analytics</a></figcaption>
	</figure>
	
	<p><?php _e('Now you can combine Twitter Cards with', 'jm-tc');?> Twitter Card analytics</p>
	<p><?php _e('It allows you to make some tests and then you can choose "top performing Twitter Cards that drove clicks".', 'jm-tc'); ?> </p>
	<p><?php _e('You can test sources, links, influencers and devices. It is awesome and you should enjoy these new tools.', 'jm-tc'); ?></p>
	<p><?php _e('This will help you to set the best card type experience and it will probably improve your marketing value.', 'jm-tc');?></p>
	
	<div class="doc-valid">
	<?php echo JM_TC_Admin::docu_links(6); ?>
	</div>
	
</div>



