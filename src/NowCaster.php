<?php

namespace Bermuda\Caster;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Class NowCaster
 *
 * A caster that always returns the current date and time as a Carbon object.
 * Implements the ValueInterface, allowing value retrieval regardless of
 * the parameter passed to the cast method.
 * Used to obtain the current moment in time within the type system.
 */
final class NowCaster implements CasterInterface, ValueInterface
{
    public function cast(mixed $value): CarbonInterface
    {
        return $this->getValue();
    }

    public function getName(): string
    {
        return 'now';
    }

    public function getValue(): CarbonInterface
    {
        return Carbon::now();
    }
}