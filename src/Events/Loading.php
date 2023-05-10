<?php

namespace Mrluke\Settings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Loading
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
     * Create a new event instance.
     *
     * @param string $driver
     * @param string $bag
     *
     * @return void
     */
    public function __construct(string $driver, string $bag)
    {
        $this->bag = $bag;
        $this->driver = $driver;
    }
}
