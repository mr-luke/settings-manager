<?php

if (!function_exists('settings')) {
    /**
     * Helper function for Settings Manager.
     *
     * @param mixed|null $key
     * @param mixed|null $def
     * @return mixed
     */
    function settings(mixed $key = null, mixed $def = null): mixed
    {
        /* @var \Mrluke\Settings\Manager $manager */
        $manager = app('mrluke-settings-manager');
        $result = $def;

        if (is_null($key)) {
            // You will get an instance of Manager.
            return $manager;
        } elseif (is_string($key)) {
            // You will get setting option with default fallback.
            $result = $manager->get($key, $def);
        } elseif (is_array($key)) {
            // You will set a new value to given key or get key
            // if array has only one element.
            if (count($key) > 1) {
                $result = $manager->set($key[0], $key[1]);
            } else {
                $result = $manager->get($key[0]);
            }
        }

        return $result;
    }
}
