<?php

namespace Mrluke\Settings;

use InvalidArgumentException;
use Mrluke\Configuration\Host;
use Mrluke\Settings\Contracts\Bag;

/**
 * Manager is main class providing settings management system.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @license   MIT
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @method   mixed get(string $key, $default = null)
 * @method   mixed register(string $key, mixed $value, string $type)
 * @method   mixed set(string $key, mixed $value)
 */
class Manager
{
    /**
     * Instances of Bag.
     *
     * @var array
     */
    private array $bags = [];

    /**
     * Raw collection of settings.
     *
     * @var \Mrluke\Configuration\Host
     */
    private Host $config;

    public function __construct(Host $configuration)
    {
        $this->config = $configuration;
    }

    /**
     * Return instance of given Bag by name.
     *
     * @param string $name
     *
     * @return \Mrluke\Settings\Contracts\Bag
     */
    public function bag(string $name): Bag
    {
        if (!array_key_exists($name, $this->config->get('bags'))) {
            throw new InvalidArgumentException(
                sprintf('Given %s bag doesn\'t exist.', $name)
            );
        }
        // Let's get or build Bag instance for given bag's name.
        return $this->getBagInstance($name);
    }

    /**
     * Return default Bag.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->bag($this->config->get('default'))->$method(...$parameters);
    }

    /**
     * Return assign bag with injected Driver.
     *
     * @param string $name
     *
     * @return \Mrluke\Settings\Contracts\Bag
     */
    protected function getBagInstance(string $name): Bag
    {
        // If there is already an instance, just return
        if (isset($this->bags[$name])) {
            return $this->bags[$name];
        }

        $bagConfig = $this->config->get('bags.' . $name);

        if (array_keys($bagConfig) != ['driver', 'cache', 'lifetime']) {
            throw new InvalidArgumentException(
                sprintf('Given %s bag is not configurated properly.', $name)
            );
        }
        // Based on driver settings let's make a new instance of class
        // through static call that check if all needed configuration
        // are present.
        //
        $driverConfig = $this->config->get('drivers.' . $bagConfig['driver']);
        $class = $driverConfig['class'];
        unset($driverConfig['class']);

        $this->bags[$name] = new SettingsBag(new $class($driverConfig, $name, $bagConfig), $name);

        return $this->bags[$name];
    }
}
