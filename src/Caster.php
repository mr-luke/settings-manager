<?php

namespace Mrluke\Settings;

/**
 * Caster is a small helper trait that provide ability
 * to cast attributes.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @license   MIT
 */
trait Caster
{
    /**
     * Cast given value to given type.
     *
     * @param  mixed  $value
     * @param  string $type
     * @return mixed
     */
    protected function cast($value, string $type)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($type) {
            case 'array':
                return is_array($value) ? $value : json_decode($value, true);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'boolean':
                return (bool) $value;
        }
        return $value;
    }
}
