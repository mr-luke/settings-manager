<?php

namespace Mrluke\Settings\Concerns;

/**
 * Castable is a small helper trait that provide ability
 * to cast attributes.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 * @license   MIT
 */
trait Castable
{
    /**
     * Cast given value to given type.
     *
     * @param mixed  $value
     * @param string $type
     *
     * @return mixed
     */
    protected function castToType($value, string $type)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($type) {
            case 'array':
                return is_array($value) ? $value : json_decode($value, true);
            case 'boolean':
                return (bool) $value;
            case 'float':
                return (float) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return $this->is_json($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE);
            case 'string':
                return (string) $value;

        }

        return $value;
    }

    /**
     * Determine if given value is json type.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function is_json($value) : bool
    {
        json_decode($value);

        return json_last_error() == JSON_ERROR_NONE;
    }
}
