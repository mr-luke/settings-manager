<?php

namespace Mrluke\Settings\Contracts;

/**
 * Bag interface for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
interface Bag
{
    /**
     * Delete setting by a given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function forget(string $key) : void;

    /**
     * Return setting of given key.
     *
     * @param string $key
     * @param null   $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed;

    /**
     * Register new setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    public function register(string $key, mixed $value, string $type): mixed;

    /**
     * Set new value for given setting.
     *
     * @param string $key
     * @param mixed  $value
     * @return mixed
     */
    public function set(string $key, mixed $value): mixed;
}
