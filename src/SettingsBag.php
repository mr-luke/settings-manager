<?php

namespace Mrluke\Settings;

use InvalidArgumentException;
use Mrluke\Settings\Contracts\Bag;
use Mrluke\Settings\Contracts\Driver;

/**
 * SettingsBag is a storage class for settings grouped by specific ID.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 * @license   MIT
 */
final class SettingsBag implements Bag
{
    /**
     * Bag of settings.
     *
     * @var array
     */
    protected array $bag;

    /**
     * Driver use to interact with settings storage.
     *
     * @var \Mrluke\Settings\Contracts\Driver
     */
    protected Driver $driver;

    /**
     * Determine if driver already loaded data.
     *
     * @var bool
     */
    protected bool $loaded = false;

    /**
     * Name of a Bag.
     *
     * @var string
     */
    public string $name;

    /**
     * List of allowed types.
     *
     * @var array
     */
    protected array $types = ['array', 'bool', 'boolean', 'double', 'float', 'integer', 'json', 'string'];

    public function __construct(Driver $driver, string $name)
    {
        $this->driver = $driver;
        $this->name = $name;
    }

    /**
     * Delete setting by a given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function forget(string $key) : void
    {
        unset($this->bag[$key]);

        $this->driver->delete($key);
    }

    /**
     * Return setting of given key.
     *
     * @param string $key
     * @param null   $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        // In case where not loaded yet.
        if (!$this->loaded) {
            $this->load();
        }

        return $this->bag[$key] ?? $default;
    }

    /**
     * Register new setting.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    public function register(string $key, $value, string $type): mixed
    {
        if (!in_array($type, $this->types)) {
            throw new InvalidArgumentException(
                sprintf('Given %s type is not allowed.', $type)
            );
        }
        if ($type == 'boolean') {
            $type = 'bool';
        }
        if ($type == 'double') {
            $type = 'float';
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
    public function set(string $key, $value)
    {
        // In case where not loaded yet.
        if (!$this->loaded) {
            $this->load();
        }

        // If there is no setting yet, register new one
        // with autodetected type.
        if (!isset($this->bag[$key])) {
            return $this->register($key, $value, gettype($value));
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
        $this->bag = $this->driver->fetch();

        $this->loaded = true;
    }
}
