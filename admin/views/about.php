<?php

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<div class="wrap tc">
    <h1><?php esc_html_e('JM Twitter Cards', 'jm-tc'); ?>: <?php echo strtolower(esc_html__('About', 'jm-tc')); ?></h1>
    <h2>
        <span><?php _e('This plugin is free', 'jm-tc'); ?></span>
    </h2>
    <?php _e('This plugin is free not me :)', 'jm-tc'); ?>
    <?php _e('So please give me some time to solve issues and answer in case you have any problem.', 'jm-tc'); ?>
    <h2><?php _e('The developer', 'jm-tc'); ?></h2>
    <?php
    $output = wpautop(__('I am a web developer, I like to make it simpler.', 'jm-tc'));

    echo $output; ?>
</div>
