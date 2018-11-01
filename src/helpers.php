<?php

if (!function_exists('settings')) {
    /**
     * Helper function for Settings Manager.
     *
     * @param mixed|null $key
     * @param mixed|null $def
     *
     * @return mixed
     */
    function settings($key = null, $def = null)
    {
        if (is_null($key)) {
            // You will get an instance of Manager.
            return app('mrluke-settings-manager');
        } elseif (is_string($key)) {
            // You will get setting option with default fallback.
            return app('mrluke-settings-manager')->get($key, $def);
        } elseif (is_array($key)) {
            // You will set a new value to given key or get key
            // if array has only one element.
            if (count($key) > 1) {
                return app('mrluke-settings-manager')->set($key[0], $key[1]);
            } else {
                return app('mrluke-settings-manager')->get($key);
            }
        }
    }
}
