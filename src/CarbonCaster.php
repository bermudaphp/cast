<?php

namespace Bermuda\Caster;

use Bermuda\DI\cast\CastableException;
use Bermuda\DI\cast\CasterInterface;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

/**
 * Class CarbonCaster
 *
 * Converts various date and time formats into a Carbon object.
 * Supports conversion of strings, integers (timestamp), and DateTimeInterface objects.
 * If the input is already a Carbon object, it is returned unchanged.
 * Throws CastableException if conversion is not possible.
 */
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

            throw CastableException::unsupported($this, $value);
        } catch (InvalidFormatException $exception) {
            throw CastableException::fromPrevious($exception, $this, $value);
        }
    }

    public function getName(): string
    {
        return 'carbon';
    }
}