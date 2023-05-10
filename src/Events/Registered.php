<?php

namespace Mrluke\Settings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Registered
{
    use Dispatchable, SerializesModels;

    /**
     * SettingsBag name.
     *
     * @var string
     */
    public string $bag;

    /**
     * Used driver instance.
     *
     * @var string
     */
    public string $driver;

    /**
     * Registered key name.
     *
     * @var string
     */
    public string $key;

    /**
     * Registered initial value.
     *
     * @var string
     */
    public mixed $value;

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
    public function __construct(string $driver, string $bag, string $key, mixed $value)
    {
        $this->bag = $bag;
        $this->driver = $driver;
        $this->key = $key;
        $this->value = $value;
    }
}
