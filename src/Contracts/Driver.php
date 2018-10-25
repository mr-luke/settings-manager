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
     * Creates instance of Driver.
     *
     * @param  array $config
     * @return Mrluke\Settings\Contracts\Driver
     *
     * @throws InvalidArgumentException
     */
    public static function createInstance(array $config) : Driver;

    /**
     * Delete given key.
     *
     * @param  string $key
     * @return void
     */
    public function delete(string $key) : void;

    /**
     * Load Raw data from storage.
     *
     * @return self
     */
    public function fetch() : self;

    /**
     * Insert new key.
     *
     * @param  string      $key
     * @param  mixed       $value
     * @param  string|null $type
     * @return mixed
     */
    public function insert(string $key, $value, string $type = null);

    /**
     * Parse Raw data to asoc array.
     *
     * @return array
     */
    public function parse() : array;

    /**
     * Update given key.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function update(string $key, $value);

    /**
     * Cast given value to given type.
     *
     * @param  mixed  $value
     * @param  string $type
     * @return mixed
     */
    protected function cast($value, string $type);

    /**
     * Return cahce lifetime in minutes.
     *
     * @return int
     */
    protected function getCacheLifetime() : int;

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    protected function isCacheEnabled() : bool;
}
