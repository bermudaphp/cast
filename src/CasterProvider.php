<?php

namespace Bermuda\Caster;

use Bermuda\Stdlib\Arrayable;

/**
 * Class CasterProvider
 *
 * Responsible for managing and supplying caster instances.
 * Casters convert values to specific types or formats.
 * The provider maintains a registry, using caster names as keys.
 *
 * Casters may be registered either as a class name (to be instantiated on demand)
 * or as direct instances of CasterInterface.
 */
final class CasterProvider implements CasterProviderInterface, \IteratorAggregate, Arrayable
{
    /**
     * An associative array of caster definitions.
     * Keys are caster names, and values are either class strings or instantiated objects.
     *
     * @var CasterInterface[]
     */
    private array $casters = [];

    /**
     * Constructor.
     *
     * Optionally initializes the provider with an iterable collection of caster instances.
     *
     * @param iterable<CasterInterface> $casters An iterable collection of caster instances.
     */
    public function __construct(iterable $casters = [])
    {
        foreach ($casters as $caster) $this->add($caster);
    }

    /**
     * Adds a caster instance to the registry.
     *
     * The caster instance is registered using its unique name
     * as determined by the caster's getName() method.
     *
     * @param CasterInterface $caster The caster instance to add.
     * @return self
     */
    public function add(CasterInterface $caster): CasterProviderInterface
    {
        $this->casters[$caster->getName()] = $caster;
        return $this;
    }

    /**
     * Returns an associative array representation of the registered casters.
     *
     * This method is part of the Arrayable interface implementation.
     *
     * @return CasterInterface[] The array representation of casters, indexed by the caster name.
     */
    public function toArray(): array
    {
        return $this->casters;
    }

    /**
     * Determines if a caster with the specified name is registered.
     *
     * @param string $name The caster name to look for.
     * @return bool Returns true if the caster exists; false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->casters[$name]);
    }

    /**
     * Provides a caster instance based on its name.
     *
     * If the given name is composite (contains a pipe "|" character), it is assumed that
     * multiple caster names have been provided. In this case, a composite PipeCaster is
     * created that combines the individual casters.
     *
     * For caster definitions stored as class strings, they are instantiated on demand.
     *
     * @param string $name The name or composite name of the caster.
     * @return CasterInterface|null The caster instance if found; otherwise, null.
     *
     * @throws \OutOfBoundsException If a composite caster references a caster that is not registered.
     */
    public function provide(string $name): ?CasterInterface
    {
        if (!isset($this->casters[$name]) && str_contains($name, '|')) {
            $casterNames = explode('|', $name);

            $pipe = [];
            foreach ($casterNames as $casterName) {
                if (!isset($this->casters[$casterName])) {
                    throw new \OutOfBoundsException("Caster '$casterName' not found for composite caster $name");
                }

                $pipe[] = $this->casters[$casterName];
            }

            return $this->casters[$name] = new PipeCaster($pipe);
        }

        return $this->casters[$name] ?? null;
    }

    /**
     * Retrieves an iterator for the registered casters.
     *
     * This supports the use of iteration constructs (e.g., foreach) on the provider.
     *
     * @return \Generator<string, CasterInterface> A generator yielding caster name => caster instance pairs.
     */
    public function getIterator(): \Generator
    {
        yield from $this->casters;
    }

    /**
     * Static method to create a default CasterProvider instance with a predefined set of casters.
     *
     * @return CasterProvider Returns a new provider preloaded with a set of common caster implementations.
     */
    public static function createDefault(): CasterProvider
    {
        return new CasterProvider([
            new ArrayCaster,
            new JsonCaster,
            new Base64Caster,
            new StringCaster,
            new IntCaster,
            new FloatCaster,
            new BooleanCaster,
            new EmailCaster,
            new TimestampCaster,
            new CarbonCaster,
            new UuidCaster,
            new SlugCaster,
            new PhoneCaster,
            new NowCaster,
            new ByteCaster,
        ]);
    }
}
