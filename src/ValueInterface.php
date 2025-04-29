<?php

namespace Bermuda\Cast;

/**
 * Interface ValueInterface
 *
 * Represents a generic value holder. Implementations of this interface are responsible
 * for encapsulating and returning a stored value.
 */
interface ValueInterface
{
    /**
     * Retrieves the stored value.
     *
     * Implementations should define how the value is stored and returned.
     *
     * @return mixed The stored value.
     */
    public function getValue(): mixed;
}
