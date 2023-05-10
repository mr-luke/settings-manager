<?php

namespace Mrluke\Settings\Drivers;

use Mrluke\Settings\Concerns\HasEvents;
use Mrluke\Settings\Contracts\Driver as DriverContract;

/**
 * Abstract driver for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
abstract class Driver implements DriverContract
{
    use HasEvents;

    /**
     * Name of a Bag.
     *
     * @var string
     */
    protected string $bag;

    /**
     * Configuration for driver.
     *
     * @var array
     */
    protected array $config;

    public function __construct(array $config, string $bagName)
    {
        $this->config = $config;
        $this->bag = $bagName;
    }

    /**
     * Delete given key.
     *
     * @param string $key
     *
     * @return void
     */
    abstract public function delete(string $key) : void;

    /**
     * Load Raw data from storage.
     *
     * @return array
     */
    abstract public function fetch() : array;

    /**
     * Insert new key.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     *
     * @return mixed
     */
    abstract public function insert(string $key, $value, string $type): mixed;

    /**
     * Update given key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    abstract public function update(string $key, $value): mixed;
}
