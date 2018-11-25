<?php

namespace Mrluke\Settings\Contracts;

/**
 * Driver interface for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
interface Driver
{
    /**
     * Delete given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key) : void;

    /**
     * Load Raw data from storage.
     *
     * @return array
     */
    public function fetch() : array;

    /**
     * Insert new key.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     *
     * @return mixed
     */
    public function insert(string $key, $value, string $type);

    /**
     * Update given key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function update(string $key, $value);
}
