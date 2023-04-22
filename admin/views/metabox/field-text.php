<?php
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<input 
    size="60" 
    class="tc-field-<?php echo !empty($array["type"]) ? esc_attr($array["type"]) : ''; ?>" 
    id="<?php esc_attr_e($array['field_id']) ; ?>" 
    name="<?php esc_attr_e($array['field_id']); ?>" 
    type="text" 
    value="'<?php esc_attr_e($array['value']); ?>"
>
