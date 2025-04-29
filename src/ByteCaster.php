<?php

namespace dicontainer\cast;

use Bermuda\Stdlib\Byte;

/**
 * Class ByteCaster
 *
 * Implements the CasterInterface to convert various input types into a Byte instance.
 * This caster wraps the Byte class constructor. If the input cannot be converted,
 * it throws a CastableException.
 */
class ByteCaster implements CasterInterface
{
    /**
     * Casts the given value into a Byte instance.
     *
     * This method attempts to create a new Byte object from the provided value.
     * If the conversion fails, it catches any thrown error and rethrows it as a CastableException.
     *
     * @param mixed $value The value to be cast to a Byte. This can be a numeric or string value
     *                     representing a byte quantity.
     * @return Byte The resulting Byte instance.
     *
     * @throws CastableException If the conversion to a Byte instance fails.
     */
    public function cast(mixed $value): Byte
    {
        try {
            return new Byte($value);
        } catch (\Throwable $e) {
            throw new CastableException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the name identifier for this caster.
     *
     * This name is used for registering and retrieving the caster from a provider.
     *
     * @return string The string "byte" that identifies this caster.
     */
    public function getName(): string
    {
        return 'byte';
    }
}
