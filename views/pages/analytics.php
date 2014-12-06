<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

?>
<div class="wrap">
    <h2>JM Twitter Cards : <?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php echo JM_TC_Tabs::admin_tabs(); ?>

    <figure>
        <img src="<?php echo esc_url(JM_TC_IMG_URL . 'doc/analytics.png'); ?>" width="90%" alt=""/>
        <figcaption class="totheright">Source : <a class="button"
                                                   href="<?php echo esc_url('https://analytics.twitter.com/'); ?>">Twitter
                Card analytics</a></figcaption>
    </figure>

    <p><?php _e('Now you can combine Twitter Cards with', JM_TC_TEXTDOMAIN); ?> Twitter Card analytics</p>

    <p><?php _e('It allows you to make some tests and then you can choose "top performing Twitter Cards that drove clicks".', JM_TC_TEXTDOMAIN); ?> </p>

    <p><?php _e('You can test sources, links, influencers and devices. It is awesome and you should enjoy these new tools.', JM_TC_TEXTDOMAIN); ?></p>

    <p><?php _e('This will help you to set the best card type experience and it will probably improve your marketing value.', JM_TC_TEXTDOMAIN); ?></p>

    <div class="doc-valid">
        <?php echo JM_TC_Admin::docu_links(6); ?>
    </div>

</div>



