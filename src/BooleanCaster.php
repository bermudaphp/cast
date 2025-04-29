<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\StrHelper;

/**
 * Class BooleanCaster
 *
 * Responsible for converting various types of values to a boolean value.
 * It explicitly handles numeric, boolean, string, and null values. For strings,
 * it relies on StrHelper::toBool to parse the value. For other types, it falls back
 * to a native boolean cast and wraps any errors in a CastableException.
 */
class BooleanCaster implements CasterInterface
{
    /**
     * Casts the given value to a boolean.
     *
     * If the value is numeric, it casts it to an integer and returns true if the result is greater than 1.
     * If it's already a boolean, it returns the value as is.
     * If it's a string, it converts the string to a boolean using StrHelper::toBool.
     * If it's null, it returns false.
     * For all other cases, it attempts a generic boolean cast.
     *
     * @param mixed $value The value to be cast.
     * @return bool The resulting boolean value.
     *
     * @throws CastableException If casting fails due to an unexpected error.
     */
    public function cast(mixed $value): bool
    {
        if (is_numeric($value)) return (int) $value > 1;
        if (is_bool($value)) return $value;
        if (is_string($value)) return StrHelper::toBool($value);
        if (is_null($value)) return false;

        try {
            return (bool) $value;
        } catch (\Throwable $e) {
            throw new CastableException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the name of this caster.
     *
     * The returned name is used to identify this caster within a registry.
     *
     * @return string The name of the caster.
     */
    public function getName(): string
    {
        return 'boolean';
    }
}
