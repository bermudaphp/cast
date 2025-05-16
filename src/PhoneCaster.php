<?php

namespace Bermuda\Caster;

use Bermuda\Stdlib\Phone;

/**
 * Class PhoneCaster
 *
 * Converts string values to Phone objects.
 * Performs validation to ensure the provided string represents a valid phone number.
 * Returns the existing Phone instance if one is provided.
 * Throws CastableException if the input cannot be converted to a valid Phone object.
 */
final class PhoneCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): Phone
    {
        if ($value instanceof Phone) return $value;
        if (is_string($value)) {
            try {
                Phone::createWithValidation($value);
            } catch (\InvalidArgumentException $e) {
                throw new CastableException($e->getMessage(), $e->getCode(), $e);
            }
        }

        throw CastableException::unsupported($this, $value);
    }

    public function getName(): string
    {
        return 'phone';
    }
}
