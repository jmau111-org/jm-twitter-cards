<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<input 
    size="60" 
    class="tc-field-<?php echo !empty($meta['type']) ? esc_attr($meta['type']) : ''; ?>-url" 
    id="<?php esc_attr_e($meta['field_id']); ?>" 
    name="<?php esc_attr_e($meta['field_id']); ?>" 
    type="url" 
    value="<?php esc_attr_e($meta['value']); ?>" 
    placeholder="https://"
>
