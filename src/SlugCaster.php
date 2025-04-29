<?php

namespace Bermuda\Cast;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;

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
            throw new CastableException('Value must be a string');
        }

        return $this->slugify->slugify($value);
    }

    public function getName(): string
    {
        return 'slug';
    }
}