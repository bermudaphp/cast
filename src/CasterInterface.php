<?php

namespace Bermuda\Caster;

use Bermuda\DI\cast\CastableExceptionInterface;

/**
 * Interface CasterInterface
 *
 * Represents a generic type caster. Implementations of this interface are responsible
 * for transforming a given value into a specific type or format.
 */
interface CasterInterface
{
    /**
     * Casts the provided value to a specific type.
     *
     * Implementations should define how the casting is performed and ensure that the returned value
     * conforms to the intended type or format.
     *
     * @param mixed $value The value to be cast.
     * @return mixed The transformed value after casting.
     *
     * @throws CastableExceptionInterface If the provided value cannot be cast properly.
     */
    public function cast(mixed $value): mixed;

    /**
     * Retrieves the name of the caster.
     *
     * This method returns a descriptive name for the caster, which can be used for identification
     * or debugging purposes.
     *
     * @return string The caster name.
     */
    public function getName(): string;
}
