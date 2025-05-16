<?php

namespace Bermuda\Caster;

use UnitEnum;

/**
 * Class EnumCasterAbstract
 *
 * Converts string or integer values to Enum instances.
 * Supports both backed enums and pure enums in PHP 8.1+.
 * Throws CastableException if the value is not a valid enum case.
 */
abstract class EnumCasterAbstract implements CasterInterface
{

    public function cast(mixed $value): object
    {
        $enum = $this->enumClass();
        try {
            // Handle backed enums
            if (is_subclass_of($enum, \BackedEnum::class)) {
                return $enum::from($value);
            }

            // Handle pure enums by name
            if (is_string($value)) {
                foreach ($enum::cases() as $case) {
                    if ($case->name === $value) {
                        return $case;
                    }
                }
            }

            throw new \ValueError("$value is not a valid case name or value");
        } catch (\Throwable $e) {
            throw CastableException::fromPrevious($e, $this, $value);
        }
    }

    /**
     * @template T of UnitEnum
     * @return class-string<T>
     */
    abstract protected function enumClass(): string ;
}