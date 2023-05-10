<?php

namespace Mrluke\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mrluke-settings-manager';
    }
}
