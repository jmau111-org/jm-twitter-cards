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

    <?php cmb_metabox_form($this->option_fields(), self::$key); ?>
    <div class="doc-valid">
        <?php echo self::docu_links(0); ?>
    </div>
    <p class="bold"><?php _e('Get more <br />from 140 characters', JM_TC_TEXTDOMAIN); ?> </p>
    <p class="sub-bold"><?php _e('with Twitter Cards', JM_TC_TEXTDOMAIN); ?></p>
    <p class="card-desc"><?php _e('Twitter Cards help you richly represent your content within<br /> Tweets across the web and on mobile devices. This gives users <br />greater context and insight into the URLs shared on Twitter,<br /> which in turn allows Twitter to<br /> send more engaged traffic to your site or app.', JM_TC_TEXTDOMAIN); ?></p>
    <p class="plugin-desc"><?php _e('With this plugin you can get summary, summary large image, product, photo, gallery, app and player cards', JM_TC_TEXTDOMAIN); ?></p>

</div>
