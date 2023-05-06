<?php

namespace JMTC\Admin;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (empty($meta['tag'])) {
    return;
}

if ('start' === $meta['where']) {
    echo '<' . esc_attr($meta['tag']) . ' class="' . esc_attr($meta['class'] ?? "") . '">';
} else {
    echo '</' . esc_attr($meta['tag']) . '>';
}
