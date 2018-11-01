<?php

namespace Mrluke\Settings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Updated
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
     * @param string $driver
     * @param string $bag
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __construct(string $driver, string $bag, string $key, $value)
    {
        $this->bag = $bag;
        $this->driver = $driver;
        $this->key = $key;
        $this->value = $value;
    }
}
