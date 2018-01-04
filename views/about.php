<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
<div class="wrap tc">
    <h1><?php esc_html_e( 'JM Twitter Cards', 'jm-tc' ); ?>: <?php echo strtolower( esc_html__( 'About' ) ); ?></h1>
    <h3><span class="icon dashicons-before dashicons-editor-code"></span><?php _e( 'The developer', 'jm-tc' ); ?></h3>
	<?php
	$output = wpautop( __( 'I am a WordPress Developer, I like to make it simple.', 'jm-tc' ) );
	$output .= '
        <ul class="socials">
            <li><a class="socials-item-link" href="https://www.twitter.com/tweetpressfr">
                <span>Twitter</span>
            </a></li>

           <li> <a class="socials-item-link" href="https://www.linkedin.com/in/julienmaury73">
                <span>Linkedin</span>
             </a></li>
	    </ul>
	';
	$output .= '<h3><span class="icon dashicons-before dashicons-heart"></span><span>' . __( 'Keep the plugin free', 'jm-tc' ) . '</span></h3>';
	$output .= wpautop( __( 'Please help if you want to keep this plugin free.', 'jm-tc' ) );
	$output .= '
    <form action="https://www.paypal.com/cgi-bin/webscr" method="POST" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="PHVFB77ZRS3UQ">
        <input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
    </form>
    ';
	$output .= '<h3><span class="icon dashicons-before dashicons-admin-plugins"></span><span>' . __( 'GitHub', 'jm-tc' ) . '</span></h3>';
	$output .= '<div id="github-repositories"></div>';

	echo $output; ?>
</div>