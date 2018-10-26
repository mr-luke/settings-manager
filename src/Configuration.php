<?php

namespace Mrluke\Settings;

use Mrluke\Settings\Contracts\ArrayHost;

/**
 * Configuration is a wrapper class provides array as object.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
final class Configuration implements ArrayHost
{
    /**
     * Configuration asoc array.
     *
     * @var array
     */
    protected $config;

    public function __construct($insert)
    {
        $this->config = $insert;
    }

    /**
     * Return given key from array.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $result = $this->iterateConfig($key);

        return is_null($result) ? $default : $result;
    }

    /**
     * Return of givent key is present.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key) : bool
    {
        $result = $this->iterateConfig($key);

        return !($result === null);
    }

    /**
     * Magic getter.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key, null);
    }

    /**
     * Iterate through configuration.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function iterateConfig(string $key)
    {
        $result = $this->config;

        foreach (explode('.', $key) as $p) {
            if (!isset($result[$p])) {
                return;
            }
            $result = $result[$p];
        }

        return $result;
    }
}
