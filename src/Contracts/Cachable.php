<?php

namespace Mrluke\Settings\Contracts;

use Closure;
use Illuminate\Support\Collection;

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
    public function flushCache() : void;

    /**
     * Return cach identifier name.
     *
     * @return string
     */
    public function getCacheIdentifier() : string;

    /**
     * Return cahce lifetime in minutes.
     *
     * @return int
     */
    public function getCacheLifetime() : int;

    /**
     * Return settings from cache.
     *
     * @param Closure $callable
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFromCache(Closure $callable): Collection;

    /**
     * Determine if cache option is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled() : bool;
}
