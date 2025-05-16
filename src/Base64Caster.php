<?php

namespace Bermuda\Caster;

/**
 * Class Base64Caster
 *
 * A caster that encodes string values into Base64 format.
 * Only accepts string values and returns their encoded version.
 * Throws CastableException if the input value is not a string.
 */
class Base64Caster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw CastableException::typeMismatch('string', $this, $value);
        }

        return base64_encode($value);
    }

    public function getName(): string
    {
        return 'base64';
    }
}