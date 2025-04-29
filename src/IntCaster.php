<?php

namespace Bermuda\Cast;

class IntCaster implements CasterInterface
{
    public function cast(mixed $value): int
    {
        if (!is_numeric($value)) {
            throw new CastableException('Casting value must be numeric');
        }

        return (int) $value;
    }

    public function getName(): string
    {
        return 'int';
    }
}