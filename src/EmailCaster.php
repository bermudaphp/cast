<?php

namespace Bermuda\Caster;

use Bermuda\DI\cast\CastableException;
use Bermuda\DI\cast\CasterInterface;
use Bermuda\Stdlib\Email;

/**
 * Class EmailCaster
 *
 * Responsible for converting strings into Email objects.
 * Performs normalization and validation of email addresses.
 * Throws CastableException if the value is not a valid email address
 * or if the input value is not a string.
 */
class EmailCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): Email
    {
        if (!is_string($value)) {
            throw CastableException::typeMismatch('string', $this, $value);
        }

        $value = Email::normalize($value);

        if (!Email::isValid($value)) {
            throw new CastableException('Casting value is not a valid email', $this, $value);
        }

        return new Email($value);
    }

    public function getName(): string
    {
        return 'email';
    }
}