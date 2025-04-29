<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Arrayable;

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
            throw new CastableException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getName(): string
    {
        return 'json';
    }
}