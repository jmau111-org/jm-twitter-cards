<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>

<input 
    type="text" 
    size="60"
    class="tc-file-input" 
    name="<?php esc_attr_e($array['field_id']); ?>" 
    id="<?php esc_attr_e($array['field_id']); ?>" 
    value="<?php esc_attr_e($array['value']); ?>"
>
<a href="#" class="tc-file-input-select button-primary">
    <?php esc_html_e('Select', 'jm-tc'); ?>
</a>
<a href="#" class="tc-file-input-reset button-secondary">
    <?php esc_html_e('Remove', 'jm-tc'); ?>
</a>
