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
    protected $bag;

    /**
     * Configuration for driver.
     *
     * @var array
     */
    protected $config;

    function __construct(array $config, string $bagName)
    {
        $this->config = $confg;
        $this->bag = $bagName;
    }
}
