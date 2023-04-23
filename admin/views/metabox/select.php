<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<select 
    class="<?php esc_attr_e($meta['field_id']); ?>" 
    id="<?php esc_attr_e($meta['field_id']); ?>"
    name="<?php esc_attr_e($meta['field_id']); ?>"
>
<?php 
foreach ($meta['options'] as $value => $label) {
    echo '<option value="' . esc_attr($value) . '"' . selected($meta['value'], $value, false) . '>';
    esc_html_e($label);
    echo '</option>';
}
?>
</select>