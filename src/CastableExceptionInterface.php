<?php

namespace Bermuda\Caster;

interface CastableExceptionInterface extends \Throwable
{
    public CasterInterface $caster { get; }
    public mixed $value { get; }
}