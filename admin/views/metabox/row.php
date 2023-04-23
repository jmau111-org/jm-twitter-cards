<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
<tr class="<?php esc_attr_e($meta["field_id"]); ?>">
    <th scope="row">
        <label for="<?php esc_attr_e($meta["field_id"]); ?>">
            <?php esc_html_e($meta["label"]); ?>
        </label>
    </th>
    <td>
        <?php require $view; ?>
    </td>
</tr>