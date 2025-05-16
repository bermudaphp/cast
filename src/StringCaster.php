<?php

namespace Bermuda\Caster;

use DateTimeInterface;
use Bermuda\Stdlib\Arrayable;

/**
 * Class StringCaster
 *
 * Versatile caster that converts various types to string representation.
 * Handles arrays (converts to JSON), objects (uses __toString, toArray, or object properties),
 * DateTimeInterface objects (formats using ATOM format), and scalar values.
 * Returns empty string for null values.
 * Throws CastableException if conversion is not supported for the given value type.
 */
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

        throw CastableException::unsupported($this, $value);
    }

    /**
     * @throws CastableException
     */
    private function jsonEncode(mixed $value): string
    {
        try {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw CastableException::fromPrevious($exception, $this, $value);
        }
    }

    public function getName(): string
    {
        return 'string';
    }
}
