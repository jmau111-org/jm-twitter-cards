<div class="metabox-holder">
	<div class="postbox inbl">
		<h3><span><?php esc_html_e( 'Export' ); ?></span></h3>

		<div class="inside">
			<form method="post">
				<p><input type="hidden" name="action" value="export_settings"/></p>

				<p>
					<?php wp_nonce_field( 'export_nonce', 'export_nonce' ); ?>
					<?php submit_button( __( 'Export' ), 'primary', 'submit', false ); ?>
				</p>
			</form>
		</div>
		<!-- .inside -->
	</div>
	<!-- .postbox -->
	<div class="postbox inbl">
		<h3><span><?php esc_html_e( 'Import' ); ?></span></h3>

		<div class="inside">
			<form method="post" enctype="multipart/form-data">
				<p>
					<input type="file" name="import_file"/>
				</p>

				<p>
					<input type="hidden" name="action" value="import_settings"/>
					<?php wp_nonce_field( 'import_nonce', 'import_nonce' ); ?>
					<?php submit_button( __( 'Import' ), 'primary', 'submit', false ); ?>
				</p>
			</form>
		</div>
		<!-- .inside -->
	</div>
	<!-- .postbox -->
</div><!-- .metabox-holder -->