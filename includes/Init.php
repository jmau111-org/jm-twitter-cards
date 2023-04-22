<?php

namespace JMTC;

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Init
{
    public static function on_activation(): void
    {
        $opts = get_option(JM_TC_SLUG_MAIN_OPTION);
        if (!is_array($opts)) {
            update_option(JM_TC_SLUG_MAIN_OPTION, jm_tc_get_default_options());
        }
    }

    public static function activate(): void
    {
        if (!is_multisite()) {
            self::on_activation();
        }
    }
}
