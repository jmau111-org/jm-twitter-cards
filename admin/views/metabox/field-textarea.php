<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<textarea 
    rows="5" 
    cols="80" 
    class="textarea tc-field-<?php echo !empty($array["type"]) ? esc_attr($array["type"]) : ''; ?>" 
    <?php echo empty($array['charcount']) ? '' : 'data-count="' . $array['charcount'] . '"'; ?>
    id="<?php esc_attr_e($array['field_id']); ?>" 
    name="<?php esc_attr_e($array['field_id']); ?>">
        <?php esc_attr_e($array['value']); ?>
</textarea>
