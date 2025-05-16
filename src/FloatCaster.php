<?php

namespace Bermuda\Caster;

/**
 * Class FloatCaster
 *
 * Converts numeric values to float type.
 * Verifies that the input value is numeric before performing the conversion.
 * Throws CastableException if the value is not numeric.
 */
class FloatCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): float
    {
        if (!is_numeric($value)) {
            throw CastableException::typeMismatch('numeric', $this, $value);
        }

        return (float) $value;
    }

    public function getName(): string
    {
        return 'float';
    }
}