<?php

namespace Bermuda\Cast;

class FloatCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): float
    {
        if (!is_numeric($value)) {
            throw new CastableException('Casting value must be numeric');
        }

        return (float) $value;
    }

    public function getName(): string
    {
        return 'float';
    }
}