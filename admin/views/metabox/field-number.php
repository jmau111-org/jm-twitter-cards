<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<input 
    type="number"    
    size="60" 
    step="<?php esc_attr_e($array['step']); ?>" 
    min="<?php esc_attr_e($array['min']); ?>" 
    max="<?php esc_attr_e($array['max']); ?>" 
    class="tc-field-<?php echo !empty($array["type"]) ? esc_attr($array["type"]) : ''; ?>" 
    id="<?php esc_attr_e($array['field_id']); ?>" 
    name="<?php esc_attr_e($array['field_id']); ?>"  
    value="<?php esc_attr_e($array['value']); ?>"
>
