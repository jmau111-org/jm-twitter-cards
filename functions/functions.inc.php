<?php
if (!defined('JM_TC_VERSION')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
 * Globalize options
 * provides filter for options
 * @return $jm_tc_options
 */

if (!function_exists('jm_tc_get_options')) {
    function jm_tc_get_options(){
        global $jm_tc_options;
        $jm_tc_options = get_option('jm_tc');
        $jm_tc_options = apply_filters('jm_tc_get_options', $jm_tc_options);
        return $jm_tc_options;
    }
}

