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
    class="textarea tc-field-<?php echo !empty($meta["type"]) ? esc_attr($meta["type"]) : ''; ?>" 
    <?php echo empty($meta['charcount']) ? '' : 'data-count="' . $meta['charcount'] . '"'; ?>
    id="<?php esc_attr_e($meta['field_id']); ?>" 
    name="<?php esc_attr_e($meta['field_id']); ?>">
        <?php esc_attr_e($meta['value']); ?>
</textarea>
