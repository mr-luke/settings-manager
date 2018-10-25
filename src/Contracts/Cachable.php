<?php

namespace Mrluke\Settings\Contracts;

/**
 * Cachable interface for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
interface Cachable
{
    /**
     * Flush settings from cache.
     *
     * @return void
     */
    protected function flushCache() : void;

    /**
     * Return cach identifier name.
     *
     * @return string
     */
    protected function getCacheIdentifier() : string;

    /**
     * Return cahce lifetime in minutes.
     *
     * @return int
     */
    protected function getCacheLifetime() : int;

    /**
     * Return settings from cache.
     *
     * @param  Closure $callable
     * @return \Illuminate\Support\Collection
     */
    protected function getFromCache(Closure $callable);

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    protected function isCacheEnabled() : bool;
}
