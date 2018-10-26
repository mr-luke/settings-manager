<?php

namespace Mrluke\Settings\Concerns;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cachable is a small helper trait that provide ability
 * to cache bag.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 * @license   MIT
 */
trait Cachable
{
    /**
     * Determine if Cache is enabled.
     *
     * @var bool
     */
    protected $cache;

    /**
     * Lifetime of cache in minutes.
     *
     * @var int
     */
    protected $lifetime;

    /**
     * Flush settings from cache.
     *
     * @return void
     */
    public function flushCache() : void
    {
        if ($this->isCacheEnabled()) {
            Cache::forget($this->getCacheIdentifier());
        }
    }

    /**
     * Return cach identifier name.
     *
     * @return string
     */
    public function getCacheIdentifier() : string
    {
        $class = explode('\\', get_class($this));
        $class = end($class);

        return  mb_strtolower('settings_'.$class.'_'.$this->bag);
    }

    /**
     * Return cache lifetime in minutes.
     *
     * @return int
     */
    public function getCacheLifetime() : int
    {
        return (int) $this->lifetime;
    }

    /**
     * Return settings from cache.
     *
     * @param Closure $callable
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFromCache(Closure $callable)
    {
        return Cache::remember($this->getCacheIdentifier(), $this->getCacheLifetime(), $callable);
    }

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled() : bool
    {
        return (bool) $this->cache;
    }

    /**
     * Set cache attributes.
     *
     * @param array $config
     *
     * @return void
     */
    public function setCache(array $config) : void
    {
        $this->cache = $config['cache'];
        $this->lifetime = $config['lifetime'];
    }
}
