<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Phone;

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

        throw new CastableException('Casting value is not supported.');
    }

    public function getName(): string
    {
        return 'phone';
    }
}
