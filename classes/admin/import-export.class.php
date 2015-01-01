<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( ! class_exists('JM_TC_Import_Export') ) {

    class JM_TC_Import_Export{
        /**
         * Constructor
         * @since 5.3.2
         */
        public function __construct(){
            $this->title = __('JM Twitter Cards', JM_TC_TEXTDOMAIN);
            add_action('admin_init', array($this, 'process_settings_export'));
            add_action('admin_init', array($this, 'process_settings_import'));
        }

        /**
         * Displays option page for importing and exporting options
         * @since 5.3.2
         */
        public static function settings_page(){
            $options = jm_tc_get_options(); ?>

            <div class="metabox-holder">
                <div class="postbox inbl">
                    <h3><span><?php _e('Export'); ?></span></h3>

                    <div class="inside">
                        <form method="post">
                            <p><input type="hidden" name="action" value="export_settings"/></p>

                            <p>
                                <?php wp_nonce_field('export_nonce', 'export_nonce'); ?>
                                <?php submit_button(__('Export'), 'primary', 'submit', false); ?>
                            </p>
                        </form>
                    </div>
                    <!-- .inside -->
                </div>
                <!-- .postbox -->
                <div class="postbox inbl">
                    <h3><span><?php _e('Import'); ?></span></h3>

                    <div class="inside">
                        <form method="post" enctype="multipart/form-data">
                            <p>
                                <input type="file" name="import_file"/>
                            </p>

                            <p>
                                <input type="hidden" name="action" value="import_settings"/>
                                <?php wp_nonce_field('import_nonce', 'import_nonce'); ?>
                                <?php submit_button(__('Import'), 'primary', 'submit', false); ?>
                            </p>
                        </form>
                    </div>
                    <!-- .inside -->
                </div>
                <!-- .postbox -->
            </div><!-- .metabox-holder -->
        <?php
        }

        /**
         * Process a settings export that generates a .json file of the shop settings
         * @since 5.3.2
         */
        function process_settings_export(){

            if (empty($_POST['action']) || 'export_settings' != $_POST['action'])
                return;

            if (!wp_verify_nonce($_POST['export_nonce'], 'export_nonce'))
                return;

            if (!current_user_can('manage_options'))
                return;

            $settings = get_option('jm_tc');

            ignore_user_abort(true);

            nocache_headers();
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=jm-twitter-cards-settings-export-' . date('m-d-Y') . '.json');
            header("Expires: 0");

            echo json_encode($settings);
            exit;
        }

        /**
         * Process a settings import from a json file
         * @since 5.3.2
         */
        function process_settings_import(){

            if (empty($_POST['action']) || 'import_settings' != $_POST['action'])
                return;

            if (!wp_verify_nonce($_POST['import_nonce'], 'import_nonce'))
                return;

            if (!current_user_can('manage_options'))
                return;

            $extension = end(explode('.', $_FILES['import_file']['name']));

            if ($extension != 'json') {
                wp_die(__('Please upload a valid .json file', JM_TC_TEXTDOMAIN));
            }

            $import_file = $_FILES['import_file']['tmp_name'];

            if (empty($import_file)) {
                wp_die(__('Please upload a file to import', JM_TC_TEXTDOMAIN));
            }

            // Retrieve the settings from the file and convert the json object to an array.
            $settings = (array)json_decode(file_get_contents($import_file));

            update_option('jm_tc', $settings);

            wp_safe_redirect(admin_url('admin.php?page=jm_tc'));
            exit;

        }

    }
}