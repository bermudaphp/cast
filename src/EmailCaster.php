<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Email;

class EmailCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): Email
    {
        if (!is_string($value)) {
            throw new CastableException('Casting value must be a string');
        }

        $value = Email::normalize($value);

        if (!Email::isValid($value)) {
            throw new CastableException('Casting value is not a valid email');
        }

        return new Email($value);
    }

    public function getName(): string
    {
        return 'email';
    }
}
