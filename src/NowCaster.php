<?php

namespace Bermuda\Cast;

use Carbon\Carbon;
use Carbon\CarbonInterface;

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