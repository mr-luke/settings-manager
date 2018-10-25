<?php

namespace Mrluke\Drivers;

use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Mrluke\Settings\Caster;
use Mrluke\Settings\Contracts\Driver;

/**
 * Database driver for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class Database implements Driver
{
    use Caster;

    /**
     * Determine if Cache is enabled.
     *
     * @var boolean
     */
    protected $cache;

    /**
     * Configuration for driver.
     *
     * @var array
     */
    protected $config;

    /**
     * Lifetime of cache in minutes.
     *
     * @var int
     */
    protected $lifetime;

    /**
     * Raw data loaded from storage.
     *
     * @var Illuminate\Support\Collection
     */
    protected $raw;

    function __construct(array $config, bool $cache, int $lifetime)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->lifetime = $lifetime;
    }

    /**
     * Creates instance of Driver.
     *
     * @param  array $config
     * @param  array $bag
     * @return Mrluke\Settings\Contracts\Driver
     *
     * @throws InvalidArgumentException
     */
    public static createInstance(array $config, array $bag) : Driver
    {
        if (array_keys($config) != ['connection', 'table']) {
            throw new InvalidArgumentException('Driver Database is not configurated properly.');
        }

        return new static($config, $bag['cache'], $bag['lifetime']);
    }

    /**
     * Delete given key.
     *
     * @param  string $key
     * @return void
     */
    public function delete(string $key) : void
    {

    }

    /**
     * Load Raw data from storage.
     *
     * @return self
     */
    public function fetch() : self
    {
        $this->raw = $this->getQuery()->get();
    }

    /**
     * Insert new key.
     *
     * @param  string      $key
     * @param  mixed       $value
     * @param  string|null $type
     * @return mixed
     */
    public function insert(string $key, $value, string $type = null)
    {

    }

    /**
     * Parse Raw data to asoc array.
     *
     * @return array
     */
    public function parse() : array
    {
        $object = $this;

        return $this->raw->flatMap(function($item) use ($object) {
            return [$item->key => $this->cast($item->value, $item->type)];
        })->toArray();
    }

    /**
     * Update given key.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function update(string $key, $value)
    {

    }

    /**
     * Return cahce lifetime in minutes.
     *
     * @return int
     */
    protected function getCacheLifetime() : int
    {

    }

    /**
     * Return QueryBuilder for a settings table.
     *
     * @return Illuminate\Database\Builder
     */
    protected function getQuery()
    {
        return DB::connection($this->config['connection'])->table($this->config['table']);
    }

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    protected function isCacheEnabled() : bool
    {

    }
}
