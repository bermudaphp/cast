<?php

namespace Bermuda\Caster;

/**
 * Interface ValueInterface
 *
 * Represents objects that can provide or generate values independently.
 * Classes implementing this interface act as value providers or factories
 * that can return values without external input parameters.
 *
 * This interface is often used in conjunction with CasterInterface to create
 * casters that generate values rather than transform input values.
 *
 * @see NowCaster For an example implementation that provides the current time.
 */
interface ValueInterface
{
    /**
     * Returns a value generated or provided by this object.
     *
     * @return mixed The generated or provided value.
     */
    public function getValue(): mixed;
}