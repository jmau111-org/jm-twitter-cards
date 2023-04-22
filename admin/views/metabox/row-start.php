<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<tr class="<?php esc_attr_e($array["field_id"]); ?>">
    <th scope="row">
        <label for="<?php esc_attr_e($array["field_id"]); ?>">
            <?php esc_html_e($array["label"]); ?>
        </label>
    </th>
    <td>
