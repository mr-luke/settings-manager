<?php

namespace Mrluke\Settings\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class Loading
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
     * Create a new event instance.
     *
     * @param Page $model
     * @return void
     */
    public function __construct(string $driver, string $bag)
    {
        $this->bag    = $bag;
        $this->driver = $driver;
    }
}
