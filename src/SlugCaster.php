<?php

namespace Bermuda\Caster;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;

/**
 * Class SlugCaster
 *
 * Converts strings into URL-friendly slug format.
 * Uses the Cocur\Slugify library for conversion.
 * Throws CastableException if the input value is not a string.
 */
class SlugCaster implements CasterInterface
{
    public function __construct(
        private readonly SlugifyInterface $slugify = new Slugify(),
    ) {
    }

    /**
     * @throws CastableException
     */
    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw CastableException::typeMismatch('string', $this, $value);
        }

        return $this->slugify->slugify($value);
    }

    public function getName(): string
    {
        return 'slug';
    }
}