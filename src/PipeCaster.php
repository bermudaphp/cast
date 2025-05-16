<?php

namespace Bermuda\Caster;

/**
 * Class PipeCaster
 *
 * A caster implementation that applies multiple casters sequentially.
 * This class iterates over each registered caster and uses the output of one as the input for the next.
 * The final result of the casting operations is then returned.
 *
 * The getName() method returns a concatenated string of all registered caster names, separated by a pipe ("|").
 */
final class PipeCaster implements CasterInterface
{
    /**
     * An array of registered casters indexed by their names.
     *
     * Each element in the array is either a class string or an instance implementing CasterInterface.
     *
     * @var CasterInterface[]
     */
    private array $casters = [];

    /**
     * Constructs a new PipeCaster instance.
     *
     * @param iterable<CasterInterface> $casters An iterable collection of caster instances
     *                                             that will be applied sequentially.
     */
    public function __construct(iterable $casters)
    {
        foreach ($casters as $caster) {
            $this->addCaster($caster);
        }
    }

    /**
     * Adds a new caster to the pipeline.
     *
     * The caster is stored in the internal registry using the caster's name, as returned by getName().
     *
     * @param CasterInterface $caster The caster instance to register.
     * @return void
     */
    private function addCaster(CasterInterface $caster): void
    {
        $this->casters[$caster->getName()] = $caster;
    }

    /**
     * Casts a given value by applying each registered caster sequentially.
     *
     * The input value is passed to the first caster, then its output is passed to the
     * next caster, and so on, until all casters have been applied. The final transformed
     * value is then returned.
     *
     * @param mixed $value The value to be cast.
     * @return mixed The value after being processed by all casters.
     * @throws CastableExceptionInterface
     */
    public function cast(mixed $value): mixed
    {
        foreach ($this->casters as $caster) {
            $value = $caster->cast($value);
        }

        return $value;
    }

    /**
     * Retrieves a concatenated string of all registered caster names.
     *
     * The names are joined together using the pipe character ("|").
     *
     * @return string A string representing the names of all casters in this pipeline.
     */
    public function getName(): string
    {
        return implode('|', array_keys($this->casters));
    }
}
