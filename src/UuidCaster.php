<?php

namespace Bermuda\Caster;

use DateTimeInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class UuidCaster implements CasterInterface
{
    public function __construct(
        private readonly UuidFactory $uuidFactory = new UuidFactory(),
    ) {
    }

    /**
     * @throws CastableException
     */
    public function cast(mixed $value): UuidInterface
    {
        if ($value instanceof UuidInterface) return $value;
        if ($value instanceof DateTimeInterface) return $this->uuidFactory->fromString($value);
        if ($value instanceof Hexadecimal) return $this->uuidFactory->fromHexadecimal($value);
        if (is_int($value)) return $this->uuidFactory->fromInteger($value);

        if (!is_string($value) || !Uuid::isValid($value)) {
            throw new CastableException('Invalid Uuid format', $this, $value);
        }

        return (string) Uuid::fromString($value);
    }

    public function getName(): string
    {
        return 'uuid';
    }
}