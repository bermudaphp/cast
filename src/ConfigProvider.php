<?php

namespace Bermuda\Caster;

use Bermuda\DI\cast\CasterProvider;

class ConfigProvider extends \Bermuda\Config\ConfigProvider
{
    protected function getFactories(): array
    {
        return [CasterProvider::class => [CasterProvider::class, 'createDefault']];
    }

    protected function getAliases(): array
    {
        return [CasterProviderInterface::class => CasterProvider::class];
    }
}