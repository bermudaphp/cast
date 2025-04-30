<?php

namespace Bermuda\Cast;

#[\Attribute(\Attribute::TARGET_PROPERTY)] class Cast
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $default = null,
    ) {
    }
}
