<?php

namespace Mrluke\Settings;

use InvalidArgumentException;
use Mrluke\Configuration\Contracts\ArrayHost;
use Mrluke\Settings\Contracts\Bag;

/**
 * Manager is main class providing settings management system.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @version   1.0
 * @license   MIT
 *
<<<<<<< HEAD
 * @method    mixed get($key)
 * @method    mixed set($key, $value)
=======
 * @method    mixed get(string $key)
>>>>>>> 06d9275ea49b4afa83b20f8d6518e78dcf5e9802
 */
class Manager
{
    /**
     * Instances of Bag.
     *
     * @var array
     */
    private $bags = [];

    /**
     * Raw collection of settings.
     *
     * @var Mrluke\Settings\Contracts\ArrayHost
     */
    private $config;

    public function __construct(ArrayHost $configuration)
    {
        $this->config = $configuration;
    }

    /**
     * Return instance of given Bag by name.
     *
     * @param string $name
     *
     * @return Mrluke\Settings\Contracts\Bag
     */
    public function bag(string $name) : Bag
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
     * @return Mrluke\Settings\Contracts\Bag
     */
    protected function getBagInstance(string $name) : Bag
    {
        // If there is already an instance, just reaturn
        if (isset($this->bags[$name])) {
            return $this->bags[$name];
        }

        $bagConfig = $this->config->get('bags.'.$name);

        if (array_keys($bagConfig) != ['driver', 'cache', 'lifetime']) {
            throw new InvalidArgumentException(
                sprintf('Given %s bag is not configurated properly.', $name)
            );
        }
        // Based on driver settings let's make a new instance of class
        // through static call that check if all needed configuration
        // are present.
        //
        $driverConfig = $this->config->get('drivers.'.$bagConfig['driver']);
        $class = $driverConfig['class'];
        unset($driverConfig['class']);

        $this->bags[$name] = new SettingsBag(new $class($driverConfig, $name, $bagConfig), $name);

        return $this->bags[$name];
    }
}
