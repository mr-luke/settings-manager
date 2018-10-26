<?php

namespace Mrluke\Settings\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class Updating
{
    use Dispatchable, SerializesModels;

    /**
     * SettingsBag name.
     *
     * @var string
     */
    public $bag;

    /**
     * Used driver instance.
     *
     * @var string
     */
    public $driver;

    /**
     * Registering key name.
     *
     * @var string
     */
    public $key;

    /**
     * Create a new event instance.
     *
     * @param Page $model
     * @return void
     */
    public function __construct(string $driver, string $bag, string $key)
    {
        $this->bag    = $bag;
        $this->driver = $driver;
        $this->key    = $key;
    }
}
