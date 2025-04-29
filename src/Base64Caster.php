<?php

namespace Bermuda\Cast;

class Base64Caster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CastableException('Casting value must be a string');
        }

        return base64_encode($value);
    }

    public function getName(): string
    {
        return 'base64';
    }
}