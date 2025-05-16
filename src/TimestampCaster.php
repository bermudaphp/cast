<?php

namespace Bermuda\Caster;

use Carbon\Carbon;

/**
 * Class TimestampCaster
 *
 * Converts date/time values into Unix timestamp (integer).
 * Uses Carbon for parsing various date formats.
 * Throws CastableException if the input cannot be parsed as a valid date/time.
 */
class TimestampCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): int
    {
        try {
            return Carbon::parse($value)->getTimestamp();
        } catch (\Exception $exception) {
            throw CastableException::fromPrevious($exception, $this, $value);
        }
    }

    public function getName(): string
    {
        return 'timestamp';
    }
}

