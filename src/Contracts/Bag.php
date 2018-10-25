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
     * @param  string $key
     * @return void
     */
    public function forget(string $key) : void;

    /**
     * Return setting of given key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Register new setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @param mixed
     */
    public function register(string $key, $value, string $type);

    /**
     * Set new value for given setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param mixed
     */
    public function set(string $key, $value);

    /**
     * Loads data via driver to bag.
     *
     * @return void
     */
    protected function load() : void;
}
