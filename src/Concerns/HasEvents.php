<?php

namespace Mrluke\Settings\Concerns;

use Mrluke\Settings\Events\Forgeting;
use Mrluke\Settings\Events\Forgot;
use Mrluke\Settings\Events\Loading;
use Mrluke\Settings\Events\Loaded;
use Mrluke\Settings\Events\Registered;
use Mrluke\Settings\Events\Registering;
use Mrluke\Settings\Events\Updated;
use Mrluke\Settings\Events\Updating;

/**
 * HasEvents is a small helper trait that provide ability
 * to fire settings events.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @license   MIT
 */
trait HasEvents
{
    /**
     * Fire event Loading.
     *
     * @param  string $key
     * @return void
     */
    protected function fireForgetingEvent(string $key) : void
    {
        event(new Forgeting(get_class($this), $this->bag, $key));
    }

    /**
     * Fire event Loaded.
     *
     * @param  string $key
     * @return void
     */
    protected function fireForgotEvent(string $key) : void
    {
        event(new Forgot(get_class($this), $this->bag, $key));
    }

    /**
     * Fire event Loading.
     *
     * @return void
     */
    protected function fireLoadingEvent() : void
    {
        event(new Loading(get_class($this), $this->bag));
    }

    /**
     * Fire event Loaded.
     *
     * @return void
     */
    protected function fireLoadedEvent() : void
    {
        event(new Loaded(get_class($this), $this->bag));
    }

    /**
     * Fire event Registered.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    protected function fireRegisteredEvent(string $key, $value) : void
    {
        event(new Registered(get_class($this), $this->bag, $key, $value));
    }

    /**
     * Fire event Registering.
     *
     * @param  string $key
     * @return void
     */
    protected function fireRegisteringEvent(string $key) : void
    {
        event(new Registering(get_class($this), $this->bag, $key));
    }

    /**
     * Fire event Updated.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    protected function fireUpdatedEvent(string $key, $value) : void
    {
        event(new Updated(get_class($this), $this->bag, $key, $value));
    }

    /**
     * Fire event Updating.
     *
     * @param  string $key
     * @return void
     */
    protected function fireUpdatingEvent(string $key) : void
    {
        event(new Updating(get_class($this), $this->bag, $key));
    }
}
