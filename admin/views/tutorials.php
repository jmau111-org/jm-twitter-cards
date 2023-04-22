<?php

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<div class="wrap tc">
    <h1><?php esc_html_e('JM Twitter Cards', 'jm-tc'); ?>: <?php echo strtolower(esc_html__('Tutorials', 'jm-tc')); ?></h1>
    <section id="tutorials" class="tutorials">
        <?php foreach ($this->get_videos() as $file => $label) : ?>
            <article>
                <h2><?php esc_html_e($label, 'jm-tc'); ?></h2>
                <?php echo $this->embed($file); ?>
            </article>
        <?php endforeach; ?>
    </section>
</div>
