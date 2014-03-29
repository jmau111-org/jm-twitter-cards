
	/*	// grab our datas
		$opts = jm_tc_get_options();

		if ($opts['twitterCardMetabox'] == 'yes')
		{
		
			<div class="tc-metabox">
		<!-- documentation -->
		<a class="button docu" target="_blank" href="<?php
				echo esc_url(admin_url().'admin.php?page=jm_tc_doc');
		?>"><?php
				_e('Documentation', 'jm-tc');
		?></a>
		<a class="button docu" target="_blank" href="<?php
				echo esc_url('https://dev.twitter.com/docs/cards/validation/validator');
		?>"><?php
				_e('Validator', 'jm-tc');
		?></a>

		<!-- Preview -->
		<section class="preview-card feature">
		<h1><?php
				_e('Preview', 'jm-tc');
		?></h1>
		<pre>
		<code class="html">
		<?php 
		 echo esc_html( _jm_tc_markup() );
		 ?>
		 </code>
		</pre>
		</section>
		
					//$regex = '~(https://|www.)(.+?)~';
					<?php if( jm_tc_get_post_thumbnail_size() >= 1 ) : ?>
			<span class="card-error"><?php  _e('Image is equal to or greater than 1MB. This will break Twitter Cards. Optimize it please, this should also improve your page load.', 'jm-tc');?></span>
		<?php endif;?>

		<!-- /Preview -->
		<?php if( get_post_meta($post->ID, 'cardPlayer', true) == '' ) : ?>
			<span class="card-error mandatory"><?php _e('Mandatory for Twitter player card!', 'jm-tc');?></span>
		<?php endif;?>
		<?php if( get_post_meta($post->ID, 'cardPlayer', true) != '' && !preg_match( $regex, get_post_meta($post->ID, 'cardPlayer', true) )  ) : ?>
			<span class="card-error"><?php _e('URL must be https!', 'jm-tc');?></span>
		<?php endif;?>
			<?php if( jm_tc_get_post_thumbnail_size() >= 1 ) : ?>
			<span class="card-error"><?php  _e('Image is equal to or greater than 1MB. This will break Twitter Cards. Optimize it please, this should also improve your page load.', 'jm-tc');?></span>
		<?php endif;?>
