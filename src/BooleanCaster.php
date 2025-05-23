<?php

namespace Bermuda\Caster;

/**
 * Class BooleanCaster
 *
 * Converts various value types to boolean.
 * Handles numeric values (true if > 1), existing booleans, strings (using StrHelper::toBool),
 * and null values (returns false).
 * For other types, attempts standard boolean casting.
 * Throws CastableException if casting fails.
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
        if (is_string($value)) return $this->toBoolean($value);
        if (is_null($value)) return false;

        try {
            return (bool) $value;
        } catch (\Throwable $e) {
            throw CastableException::fromPrevious($e, $this, $value);
        }
    }

    private function toBoolean(mixed $value): bool
    {
        $value = strtolower($value);

        if (in_array($value, ['1', 'y', 'yes', 'on'])) {
            return true;
        }

        if (in_array($value, ['0', 'n', 'no', 'off'])) {
            return false;
        }

        return (bool) $value;
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
