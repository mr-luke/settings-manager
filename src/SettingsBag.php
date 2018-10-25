<?php

namespace Mrluke\Settings;

use InvalidArgumentException;
use Mrluke\Settings\Contracts\Bag;

/**
 * SettingsBag is a storage class for settings grouped by specific ID.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @version   1.0
 *
 * @license   MIT
 */
final class SettingsBag implements Bag
{
    /**
     * Bag of settings.
     *
     * @var array
     */
    protected $bag;

    /**
     * Driver use to interact with settings storage.
     *
     * @var Mrluke\Settings\Contracts\Driver
     */
    protected $driver;

    /**
     * Determine if driver already loaded data.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Name of a Bag.
     *
     * @var string
     */
    public $name;

    /**
     * List of allowed types.
     *
     * @var array
     */
    protected $types = ['array', 'boolean', 'float', 'integer', 'json', 'string'];

    function __construct(Driver $driver, string $name)
    {
        $this->driver = $driver;
        $this->name = $name;
    }

    /**
     * Delete setting by a given key.
     *
     * @param  string $key
     * @return void
     */
    public function forget(string $key) : void
    {
        unset($this->bag[$key]);

        return $this->driver->delete($key);
    }

    /**
     * Return setting of given key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // In case where not loaded yet.
        if (!$this->loaded) $this->load();

        return $this->bag[$key] ?? $default;
    }

    /**
     * Register new setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @param mixed
     */
    public function register(string $key, $value, string $type)
    {
        if (!in_array($type, $this->types)) {
            throw new InvalidArgumentException(
                sprintf('Given %s type is not allowed.', $type)
            );
        }

        $this->bag[$key] = $this->driver->insert($key, $value, $type);

        return $this->bag[$key];
    }

    /**
     * Set new value for given setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param mixed
     */
    public function set(string $key, $value) // TODO
    {
        // In case where not loaded yet.
        if (!$this->loaded) $this->load();

        // If there is no setting yet, register new one
        // with autodetected type.
        if (!isset($this->bag[$key])) {
            $this->register($key, $value, gettype($value));
        }

        $this->bag[$key] = $this->driver->update($key, $value);

        return $this->bag[$key];
    }

    /**
     * Loads data via driver to bag.
     *
     * @return void
     */
    protected function load() : void
    {
        $this->bag = $this->driver->fetch()->parse();

        $this->loaded = true;
    }
}
