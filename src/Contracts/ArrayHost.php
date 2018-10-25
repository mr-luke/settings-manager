<?php

namespace Mrluke\Settings\Contracts;

/**
 * ArrayHost is an interface for array wrapped up as Object.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @category  Laravel
 * @license   MIT
 */
interface ArrayHost
{
    /**
     * Return given key from array.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Return of givent key is present.
     *
     * @param  string $key
     * @return boolean
     */
    public function has(string $key) : bool;

    /**
     * Magic getter.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key);
}
