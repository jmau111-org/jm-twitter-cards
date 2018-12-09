<?php

use \TokenToMe\TwitterCards\Utils;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
if ( ! empty( $this->video_files ) ) : ?>
<div class="wrap tc">
    <h1><?php esc_html_e( 'JM Twitter Cards', 'jm-tc' ); ?>: <?php echo strtolower( esc_html__( 'Tutorials', 'jm-tc' ) ); ?></h1>
    <section id="tutorials" class="tutorials">
        <?php foreach ( $this->video_files  as $file => $label ) : ?>
        <article>
            <h2><?php esc_html_e( $label, 'jm-tc' ); ?></h2>
            <?php echo Utils::embed( $file ); ?>
        </article>
        <?php endforeach; ?>
    </section>
</div>
<?php endif;