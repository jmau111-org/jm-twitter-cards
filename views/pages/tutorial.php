<?php
namespace TokenToMe\TwitterCards\Admin;

if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

?>
<div class="wrap">
    <h2>JM Twitter Cards : <?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php echo Tabs::admin_tabs(); ?>

    <?php $data = array(
        __('Start', JM_TC_TEXTDOMAIN) => '', /*TODO : redo tutorial*/
    );

    $tutorials = Utilities::display_footage($data);
    echo $tutorials;
    ?>
</div>