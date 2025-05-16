<?php

namespace Bermuda\Caster;

use Bermuda\Stdlib\Arrayable;

/**
 * Class JsonCaster
 *
 * Converts values to a JSON string.
 * Supports objects implementing the Arrayable interface, converting them to arrays before encoding.
 * Allows setting JSON encoding flags during object creation or at cast method call time.
 * Throws CastableException on encoding errors.
 */
class JsonCaster implements CasterInterface
{
    public function __construct(
        public readonly int $flags = 0
    ) {
    }

    /**
     * @throws CastableException
     */
    public function cast(mixed $value, ?int $flags = null): string
    {
        if ($value instanceof Arrayable) $value = $value->toArray();

        try {
            return json_encode($value, ($flags ?? $this->flags)|JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw CastableException::fromPrevious($exception, $this, $value);
        }
    }

    public function getName(): string
    {
        return 'json';
    }
}