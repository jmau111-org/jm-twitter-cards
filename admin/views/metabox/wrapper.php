<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (empty($array['tag'])) {
    return;
}

if ('start' === $array['where']) {
    echo '<' . esc_attr($array['tag']) . ' class="' . $class . '">';
} else {
    echo '</' . esc_attr($array['tag']) . '>';
}
