<?php

namespace Bermuda\Cast;

use Bermuda\Stdlib\Arrayable;

/**
 * Class CasterProvider
 *
 * A provider responsible for managing and supplying caster instances.
 * Casters are used to convert values into specific types or formats.
 *
 * This provider maintains a registry of casters, where each caster is identified by its name.
 * Casters can be registered either as a class string or as an instance of CasterInterface.
 * When a caster is requested via the provide() method, if it's stored as a class string,
 * it will be instantiated on demand.
 */
final class CasterProvider implements \IteratorAggregate, Arrayable
{
    /**
     * An array of caster definitions indexed by the caster's name.
     *
     * Casters are stored either as class string names or as instantiated objects.
     *
     * @var CasterInterface[]
     */
    private array $casters = [
        'json' => JsonCaster::class,
        'array' => ArrayCaster::class,
        'base64' => Base64Caster::class,
        'string' => StringCaster::class,
        'int' => IntCaster::class,
        'float' => FloatCaster::class,
        'bool' => BooleanCaster::class,
        'email' => EmailCaster::class,
        'timestamp' => TimestampCaster::class,
        'carbon' => CarbonCaster::class,
        'uuid' => UuidCaster::class,
        'slug' => SlugCaster::class,
        'phone' => PhoneCaster::class,
        'now' => NowCaster::class,
    ];

    /**
     * Constructor.
     *
     * Registers additional caster instances provided in the iterable.
     *
     * @param iterable<CasterInterface> $casters An iterable collection of caster instances.
     */
    public function __construct(iterable $casters = [])
    {
        foreach ($casters as $caster) {
            $this->add($caster);
        }
    }

    /**
     * Adds a caster instance to the registry.
     *
     * The caster is registered using the name returned by its getName() method.
     *
     * @param CasterInterface $caster The caster instance to add.
     * @return void
     */
    public function add(CasterInterface $caster): void
    {
        $this->casters[$caster->getName()] = $caster;
    }

    /**
     * Returns an array representation of the registered casters.
     *
     * @return array The array of caster definitions indexed by caster name.
     */
    public function toArray(): array
    {
        return array_map($this->casters);
    }

    /**
     * Checks if a caster with the given name is registered.
     *
     * @param string $name The name of the caster.
     * @return bool True if the caster is registered; false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->casters[$name]);
    }

    /**
     * Provides a caster instance by its name.
     *
     * If the caster is stored as a class string and the name contains a pipe ("|"),
     * then multiple caster names are assumed to be provided and a PipeCaster is created to combine them.
     * If the caster is stored as a class string, it is instantiated on demand.
     *
     * @param string $name The name (or composite name) of the caster.
     * @return CasterInterface|null The caster instance, or null if no caster is registered under the provided name.
     *
     * @throws \OutOfBoundsException If one of the composite casters is not found.
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

                $instance = $this->casters[$casterName];
                if (is_string($instance)) {
                    $instance = new $instance();
                    $this->casters[$casterName] = $instance;
                }
                $pipe[] = $instance;
            }

            return $this->casters[$name] = new PipeCaster($pipe);
        }

        $caster = $this->casters[$name] ?? null;
        if (is_string($caster)) {
            return $this->casters[$name] = new $caster();
        }

        return $caster;
    }

    /**
     * Retrieves an iterator for the registered casters.
     *
     * This method allows iteration over the casters using constructs such as foreach.
     *
     * @return \Generator<string, CasterInterface> A generator yielding caster name => caster instance pairs.
     */
    public function getIterator(): \Generator
    {
        yield from $this->toArray();
    }
}
