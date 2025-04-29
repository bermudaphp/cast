<?php

namespace Bermuda\Cast;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class CarbonCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): Carbon
    {
        try {
            if ($value instanceof Carbon) {
                return $value;
            }
            elseif (is_string($value) || is_int($value)) {
                return Carbon::parse($value);
            }
            elseif ($value instanceof \DateTimeInterface) {
                return Carbon::instance($value);
            }

            throw new CastableException('Casting value is not supported');
        } catch (InvalidFormatException $exception) {
            throw new CastableException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getName(): string
    {
        return 'carbon';
    }
}