<?php

namespace Mrluke\Settings\Drivers;

use InvalidArgumentException;
use Mrluke\Settings\Concerns\Castable;

/**
 * Json driver for SettingsManager.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class Json extends Driver
{
    use Castable;

    /**
     * Raw data loaded from storage.
     *
     * @var array
     */
    protected $raw;

    public function __construct(array $config, string $bagName, array $bagConfig)
    {
        if (array_keys($config) != ['path', 'file']) {
            throw new InvalidArgumentException('Driver Json is not configurated properly.');
        }

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

        $this->loadIfNotLoaded();

        unset($this->raw[$key]);

        $this->saveContent();

        $this->fireForgotEvent($key);
    }

    /**
     * Load Raw data from storage.
     *
     * @return self
     */
    public function fetch() : array
    {
        $this->fireLoadingEvent();

        $this->loadIfNotLoaded();

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

        $this->loadIfNotLoaded();

        $this->raw[$key] = [
            'type'  => $type,
            'value' => $value
        ];

        $this->saveContent();

        $this->fireRegisteredEvent($key, $value);

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

        $this->loadIfNotLoaded();

        $this->raw[$key]['value'] = $value;

        $this->saveContent();

        $this->fireUpdatedEvent($key, $value);

        return $value;
    }

    /**
     * Loads JSON from file if not loaded yet.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function loadIfNotLoaded(): void
    {
        $fullPath = $this->getFullPath();

        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException(
                sprintf('Following file %s does not exstis.', $fullPath)
            );
        }

        if (is_null($this->raw)) {
            $this->raw = json_decode(file_get_contents($fullPath), true);
        }
    }

    /**
     * Parse from raw array to public.
     *
     * @return array
     */
    protected function parse(): array
    {
        $object = $this;

        return collect($this->raw)->flatMap(function ($item, $key) use ($object) {
            return [$key => $this->castToType($item['value'], $item['type'])];
        })->toArray();
    }

    /**
     * Save content to json file.
     *
     * @return void
     */
    protected function saveContent(): void
    {
        $fp = fopen($this->getFullPath(), 'w');
        fwrite($fp, json_encode($this->raw, JSON_PRETTY_PRINT));
        fclose($fp);
    }

    /**
     * Return full path to file.
     *
     * @return string
     */
    private function getFullPath(): string
    {
        return $this->config['path'].$this->config['file'];
    }
}
