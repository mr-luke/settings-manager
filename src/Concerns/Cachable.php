<?php

namespace Mrluke\Settings\Concerns;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cachable is a small helper trait that provide ability
 * to cache bag.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @license   MIT
 */
trait Cachable
{
    /**
     * Determine if Cache is enabled.
     *
     * @var boolean
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
    protected function flushCache() : void
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
    protected function getCacheIdentifier() : string
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
    protected function getCacheLifetime() : int
    {
        return (int) $this->lifetime;
    }

    /**
     * Return settings from cache.
     *
     * @param  Closure $callable
     * @return \Illuminate\Support\Collection
     */
    protected function getFromCache(Closure $callable)
    {
        return Cache::remember($this->getCacheIdentifier(), $this->getCacheLifetime(), $callable);
    }

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    protected function isCacheEnabled() : bool
    {
        return (bool) $this->cache;
    }

    /**
     * Set cache attributes.
     *
     * @param  array $config
     * @return void
     */
    protected function setCache(array $config) : void
    {
        $this->cache = $config['cache'];
        $this->lifetime = $config['lifetime'];
    }
}
