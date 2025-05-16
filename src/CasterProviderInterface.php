<?php

namespace Bermuda\Caster;

interface CasterProviderInterface
{
    /**
     * Provides a caster instance based on its name.
     *
     * @param string $name The name of the caster.
     * @return CasterInterface|null The caster instance if found; otherwise, null.
     *
     * @throws \OutOfBoundsException If a caster not found.
     */
    public function provide(string $name):? CasterInterface;
}