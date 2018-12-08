<?php

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
<div class="wrap tc">
    <h1><?php esc_html_e( 'JM Twitter Cards', 'jm-tc' ); ?>: <?php echo strtolower( esc_html__( 'Import', 'jm-tc' ) ); ?>
        / <?php echo strtolower( esc_html__( 'Export', 'jm-tc' ) ); ?></h1>
    <div class="holder">
        <div class="inbox">
            <h3><span><?php esc_html_e( 'Export', 'jm-tc' ); ?></span></h3>

            <div class="inside">
                <form method="post">
                    <p><input type="hidden" name="action" value="export_settings"/></p>
                    <p>
						<?php wp_nonce_field( 'export_nonce', 'export_nonce' ); ?>
						<?php submit_button( __( 'Export', 'jm-tc' ), 'primary', 'submit', false ); ?>
                    </p>
                </form>
            </div>
            <!-- .inside -->
        </div>
        <!-- .inbox -->
        <div class="inbox">
            <h3><span><?php esc_html_e( 'Import', 'jm-tc' ); ?></span></h3>

            <div class="inside">
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <input type="file" name="import_file"/>
                    </p>

                    <p>
                        <input type="hidden" name="action" value="import_settings"/>
						<?php wp_nonce_field( 'import_nonce', 'import_nonce' ); ?>
						<?php submit_button( __( 'Import', 'jm-tc' ), 'primary', 'submit', false ); ?>
                    </p>
                </form>
            </div>
            <!-- .inside -->
        </div>
        <!-- .inbox -->
    </div>
</div>