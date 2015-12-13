<?php

if ( ! function_exists( 'add_action' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}
?>
<div class="wrap">
    <h1><?php esc_html_e( 'JM Twitter Cards', 'jm-tc' ); ?>: <?php echo strtolower( esc_html__( 'About' ) ); ?></h1>
    <h3 class="hndle"><?php _e( 'Tutorial', 'jm-tc' ); ?></h3>
    <?php
    // needs update
    //echo wp_oembed_get( 'https://www.youtube.com/watch?v=8l4k3zrD4Z0' ); ?>


    <h3 class="hndle"><?php _e( 'The developer', 'jm-tc' ); ?></h3>
    <?php
    $output  = wpautop( __( 'I am a WordPress Developer, I like to make it simple.', 'jm-tc' ) );
    $output .= '<a class="social button button-secondary dashicons-before dashicons-admin-site" href="http://tweetpressfr.github.io" target="_blank" title="' . esc_attr__( 'My website', 'jm-tc' ) . '"><span class="visually-hidden">' . __( 'My website', 'jm-tc' ) . '</span></a>';
    $output .= '<a class="social button button-secondary link-like dashicons-before dashicons-twitter" href="http://twitter.com/intent/user?screen_name=tweetpressfr" title="' . esc_attr__( 'Follow me', 'jm-tc' ) . '"> <span class="visually-hidden">' . __( 'Follow me', 'jm-tc' ) . '</span></a>';
    $output .= '<a class="social button button-secondary dashicons-before dashicons-googleplus" href="https://plus.google.com/u/0/+JulienMaury" target="_blank" title="' . esc_attr__( 'Add me to your circles', 'jm-tc' ) . '"> <span class="visually-hidden">' . __( 'Add me to your circles', 'jm-tc' ) . '</span></a>';
    $output .= '<h3><span>' . __( 'Keep the plugin free', 'jm-tc' ) . '</span></h3>';
    $output .= get_avatar( 'contact@tweetpress.fr' );
    $output .= wpautop( __( 'Please help if you want to keep this plugin free.', 'jm-tc' ) );
    $output .= '
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="7BJYYT486HEH6">
        <input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
    </form>
    ';
    echo $output; ?>
</div>