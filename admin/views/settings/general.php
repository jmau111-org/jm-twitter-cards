<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<div class="wrap tc">
    <h1><?php esc_html_e('JM Twitter Cards', 'jm-tc'); ?></h1>

    <!-- DOCUMENTATION -->
    <div class="brandnew">
        <p class="description">
        <?php printf(
            '%s', 
            wp_kses_post(
                sprintf(
                    __(
                        '10.0.0 : Please see the new <a href="%s">Tutorial page</a> which can help you.', 
                        'jm-tc'
                    ), 
                    add_query_arg(
                        'page', 
                        'jm_tc_tutorials', 
                        admin_url('admin.php')
                    )
                )
            )
        ); ?>
        </p>
    </div>
    <!-- /DOCUMENTATION -->

    <!-- FORM -->
    <?php settings_errors();?>
    <div class="metabox-holder">
        <div id="tabs" class="tabs">
        <!-- NAV -->
        <?php if ($count > 1) : ?>
            <h2 class="nav-tab-wrapper">
            <?php
            foreach ($sections as $k => $tab) {
                $k++;
                echo sprintf('<a href="#tabs-%d" id="#tabs-%d" class="nav-tab">%s</a>', $k, $k, $tab['title']);
            }
            ?>
            </h2>
            <?php endif; ?>
            <!-- /NAV -->
            <?php foreach ($sections as $k => $form) { ?>
                <form method="POST" action="options.php">
                    <div id="tabs-<?php echo $k + 1; ?>">
                        <?php
                        do_action('wsa_form_top_' . $form['id'], $form);
                        settings_fields($form['id']);
                        do_settings_sections($form['id']);
                        do_action('wsa_form_bottom_' . $form['id'], $form);
                        ?>
                        <span style="padding-left: 10px;">
                            <?php submit_button(); ?>
                        </span>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
    <!-- /FORM -->
</div>