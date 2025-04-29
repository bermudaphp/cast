<?php

namespace Bermuda\Cast;

use Carbon\Carbon;

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
            throw new CastableException('Invalid date format', $exception->getCode(), $exception);
        }
    }

    public function getName(): string
    {
        return 'timestamp';
    }
}

