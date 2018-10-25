<?php

namespace Mrluke\Settings\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class Registered
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
     * Registered key name.
     *
     * @var string
     */
    public $key;

    /**
     * Registered initial value.
     *
     * @var string
     */
    public $value;

    /**
     * Create a new event instance.
     *
     * @param Page $model
     * @return void
     */
    public function __construct(string $driver, string $bag, string $key, $value)
    {
        $this->bag    = $bag;
        $this->driver = $driver;
        $this->key    = $key;
        $this->value  = $value;
    }
}
