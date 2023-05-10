<?php

namespace Mrluke\Settings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Forgot
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
     * Create a new event instance.
     *
     * @param string $driver
     * @param string $bag
     * @param string $key
     *
     * @return void
     */
    public function __construct(string $driver, string $bag, string $key)
    {
        $this->bag = $bag;
        $this->driver = $driver;
        $this->key = $key;
    }
}
