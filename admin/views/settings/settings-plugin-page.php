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
<?php $settings->show_forms(); ?>
</div>