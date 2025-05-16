<?php

namespace Bermuda\Caster;

/**
 * Class IntCaster
 *
 * Converts numeric values to integer type.
 * Verifies that the input value is numeric before performing the conversion.
 * Throws CastableException if the value is not numeric.
 */
class IntCaster implements CasterInterface
{
    public function cast(mixed $value): int
    {
        if (!is_numeric($value)) {
            throw CastableException::typeMismatch('numeric', $this, $value);
        }

        return (int) $value;
    }

    public function getName(): string
    {
        return 'int';
    }
}