<?php

use App\Models\SystemSetting;

if (!function_exists('setting')) {
    /**
     * Get or set system setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return SystemSetting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set system setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return \App\Models\SystemSetting
     */
    function set_setting(string $key, $value, string $type = 'text', ?string $description = null)
    {
        return SystemSetting::set($key, $value, $type, $description);
    }
}
