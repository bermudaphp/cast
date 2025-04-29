<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Arrayable;
use DateTimeInterface;

class StringCaster implements CasterInterface
{
    /**
     * @throws CastableException
     */
    public function cast(mixed $value): string
    {
        if (is_array($value)) return $this->jsonEncode($value);
        elseif (is_object($value)) {
            if ($value instanceof \Stringable) return $value->__toString();
            elseif ($value instanceof Arrayable) return $this->jsonEncode($value->toArray());
            elseif ($value instanceof \JsonSerializable) return $this->jsonEncode($value->jsonSerialize());
            elseif ($value instanceof DateTimeInterface) return $value->format(DateTimeInterface::ATOM);
            return $this->jsonEncode(get_object_vars($value));
        }
        elseif (is_string($value)) return $value;
        elseif ($value === null) return '';
        elseif (is_scalar($value)) return (string) $value;

        throw new CastableException('Casting value is not supported');
    }

    private function jsonEncode(mixed $value): string
    {
        try {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new CastableException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getName(): string
    {
        return 'string';
    }
}