<?php

namespace Bermuda\Caster;

/**
 * Interface CasterProviderInterface
 *
 * Defines a registry for type caster instances within a dependency injection system.
 * This interface enables the dynamic retrieval and registration of casters by name,
 * allowing for a flexible type conversion system.
 *
 * Caster providers act as a central repository for all available casters in an application,
 * facilitating type conversion during dependency resolution or value processing.
 *
 * Typical implementations include registering standard casters during initialization
 * and allowing the addition of custom casters at runtime.
 */
interface CasterProviderInterface
{
    /**
     * Provides a caster instance based on its name.
     *
     * Retrieves the caster registered under the specified name. If a composite
     * name is provided (using pipe syntax, e.g., "string|json"), implementations
     * should handle the creation of appropriate composite casters.
     *
     * @param string $name The name of the caster to retrieve.
     * @return CasterInterface|null The caster instance if found; otherwise, null.
     *
     * @throws \OutOfBoundsException If a requested caster cannot be found and is required.
     */
    public function provide(string $name):? CasterInterface;
    
    /**
     * Adds a caster to the provider's registry.
     *
     * Registers a new caster instance using the name provided by the caster's
     * getName() method as the lookup key. If a caster with the same name already
     * exists, implementations typically replace the existing entry.
     *
     * @param CasterInterface $caster The caster instance to add to the registry.
     * @return self Returns the provider instance for method chaining.
     */
    public function add(CasterInterface $caster): CasterProviderInterface ;
}
