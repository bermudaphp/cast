<?php

namespace Bermuda\Caster;

class CastableException extends \Exception implements CastableExceptionInterface
{
    public function __construct(
        string $message,
        public readonly CasterInterface $caster,
        public readonly mixed $value,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function fromPrevious(\Throwable $e, CasterInterface $caster, mixed $value): self
    {
        return new static($e->getMessage(), $caster, $value, $e);
    }

    public static function unsupported(CasterInterface $caster, mixed $value): self
    {
        return new static('Casting value is not supported', $caster, $value);
    }

    public static function typeMismatch(string $expected, CasterInterface $caster, mixed $value): self
    {
        return new static(
            sprintf("Type mismatch: expected %s, got %s during casting operation",
                $expected,
                get_debug_type($value)
            ),
            $caster,
            $value
        );
    }
}