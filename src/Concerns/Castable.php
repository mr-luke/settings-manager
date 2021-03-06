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
    public function castToType($value, string $type)
    {
        return is_null($value) ? $value : $this->{'cast'.ucfirst($type)}($value);
    }

    /**
     * Cast value to array type.
     *
     * @param  mixed $value
     * @return array
     */
    private function castArray($value): array
    {
        return is_array($value) ? $value : (array) json_decode($value, true);
    }

    /**
     * Cast value to bool type.
     *
     * @param  mixed $value
     * @return bool
     */
    private function castBool($value): bool
    {
        return (bool) $value;
    }

    /**
     * Cast value to Float type.
     *
     * @param  mixed $value
     * @return float
     */
    private function castFloat($value): float
    {
        return (float) $value;
    }

    /**
     * Cast value to  type.
     *
     * @param  mixed $value
     * @return int
     */
    private function castInteger($value): int
    {
        return (int) $value;
    }

    /**
     * Cast value to Json type.
     *
     * @param  mixed $value
     * @return string
     */
    private function castJson($value): string
    {
        if (!is_string($value)) {
            $value = is_array($value) ? json_encode($value) : (string) $value;
        }

        json_decode($value);

        return (json_last_error() == JSON_ERROR_NONE) ?
                    $value : json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cast value to string type.
     *
     * @param  mixed $value
     * @return string
     */
    private function castString($value): string
    {
        return (string) $value;
    }
}
