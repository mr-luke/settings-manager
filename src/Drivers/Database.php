<?php

namespace Mrluke\Settings\Drivers;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Mrluke\Settings\Concerns\Cachable;
use Mrluke\Settings\Concerns\Castable;
use Mrluke\Settings\Contracts\Cachable as CachableContract;

/**
 * Database driver for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class Database extends Driver implements CachableContract
{
    use Cachable, Castable;

    /**
     * Raw data loaded from storage.
     *
     * @var Illuminate\Support\Collection
     */
    protected $raw;

    public function __construct(array $config, string $bagName, array $bagConfig)
    {
        if (array_keys($config) != ['connection', 'table']) {
            throw new InvalidArgumentException('Driver Database is not configurated properly.');
        }

        $this->setCache($bagConfig);

        parent::__construct($config, $bagName);
    }

    /**
     * Delete given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key) : void
    {
        $this->fireForgetingEvent($key);

        $this->getQuery()->where('key', $key)->delete();

        $this->fireForgotEvent($key);
        $this->flushCache();
    }

    /**
     * Load Raw data from storage.
     *
     * @return array
     */
    public function fetch() : array
    {
        $this->fireLoadingEvent();

        if ($this->isCacheEnabled()) {
            $object = $this;

            $this->raw = $this->getFromCache(function () use ($object) {
                return $object->getQuery()->get();
            });
        } else {
            $this->raw = $this->getQuery()->get();
        }

        $this->fireLoadedEvent();

        return $this->parse();
    }

    /**
     * Insert new key.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     *
     * @return mixed
     */
    public function insert(string $key, $value, string $type)
    {
        $this->fireRegisteringEvent($key);

        $value = $this->castToType($value, $type);

        $this->getRawQuery()->insert([
            'bag'   => $this->bag,
            'key'   => $key,
            'type'  => $type,
            'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value,
        ]);

        if (empty($this->raw)) {
            $this->raw = collect();
        }

        $this->raw->push([
            'bag'   => $this->bag,
            'key'   => $key,
            'type'  => $type,
            'value' => $value,
        ]);

        $this->fireRegisteredEvent($key, $value);
        $this->flushCache();

        return $value;
    }

    /**
     * Update given key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function update(string $key, $value)
    {
        $this->fireUpdatingEvent($key);

        $type = $this->raw->where('key', $key)->first()['type'];
        $value = $this->castToType($value, $type);

        $this->getQuery()->where('key', $key)->update([
            'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value,
        ]);

        $this->fireUpdatedEvent($key, $value);
        $this->flushCache();

        return $value;
    }

    /**
     * Return QueryBuilder for a settings table.
     *
     * @return Illuminate\Database\Builder
     */
    protected function getRawQuery()
    {
        return DB::connection($this->config['connection'])->table($this->config['table']);
    }

    /**
     * Return QueryBuilder for a settings table.
     *
     * @return Illuminate\Database\Builder
     */
    protected function getQuery()
    {
        return $this->getRawQuery()->where('bag', $this->bag);
    }

    /**
     * Parse Raw data to asoc array.
     *
     * @return array
     */
    protected function parse() : array
    {
        $object = $this;

        return $this->raw->flatMap(function ($item) use ($object) {
            return [$item->key => $this->castToType($item->value, $item->type)];
        })->toArray();
    }
}
