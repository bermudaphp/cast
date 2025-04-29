<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Arrayable;

/**
 * Class ArrayCaster
 *
 * This caster converts various types of values into an array.
 * It handles input values as follows:
 * - Arrays are returned as-is.
 * - Strings are interpreted as JSON and decoded into an array.
 * - Objects implementing Arrayable are converted using their toArray() method.
 * - Iterables are converted via iterator_to_array().
 * - Other objects are converted to an array using get_object_vars(), or if they implement JsonSerializable,
 *   their jsonSerialize() result is decoded as JSON.
 * - Null values become an empty array.
 * - For any other type, the value is wrapped in a single-element array.
 */
class ArrayCaster implements CasterInterface
{
    /**
     * Casts the given value to an array.
     *
     * @param mixed $value The value to be cast.
     * @return array The resulting array after conversion.
     *
     * @throws CastableException If JSON decoding fails.
     */
    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif (is_string($value)) {
            return $this->jsonDecode($value);
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        } elseif (is_iterable($value)) {
            return iterator_to_array($value);
        } elseif (is_object($value)) {
            if ($value instanceof \JsonSerializable) {
                return $this->jsonDecode($value->jsonSerialize());
            }
            return get_object_vars($value);
        } elseif (is_null($value)) {
            return [];
        } else {
            return [$value];
        }
    }

    /**
     * Decodes a JSON string into an array.
     *
     * Wraps the json_decode() function with error handling.
     *
     * @param string $value The JSON string to decode.
     * @return array The decoded array.
     *
     * @throws CastableException If an error occurs during the JSON decoding process.
     */
    private function jsonDecode(string $value): array
    {
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new CastableException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Returns the name of this caster.
     *
     * This name is used as an identifier for the caster in a registry or collection.
     *
     * @return string The name of the caster, which is "array".
     */
    public function getName(): string
    {
        return 'array';
    }
}
