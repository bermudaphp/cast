# Bermuda Cast - Type Conversion Library

*Читайте на [русском](README.md)*

`bermudaphp/cast` is a lightweight yet powerful library for type conversion in PHP applications. It provides robust and flexible data transformation between different types with extensive customization capabilities.

## Installation

```bash
composer require bermudaphp/cast
```

## Key Features

- 🔄 **Type Conversion** - safe and predictable transformation between various data types
- 🧩 **Extensibility** - easy creation of custom casters for your own types
- 🔌 **Centralized Management** - single registry for all converters
- 🛡️ **Validation** - built-in validation and error handling during conversion
- 📦 **Composition** - ability to combine casters for complex transformations

## Quick Start

```php
<?php

use Bermuda\Caster\CasterProvider;
use Bermuda\Caster\IntCaster;
use Bermuda\Caster\StringCaster;
use Bermuda\Caster\BooleanCaster;
use Bermuda\Caster\CastableException;

// Create a caster provider
$provider = new CasterProvider([
    new IntCaster(),
    new StringCaster(),
    new BooleanCaster(),
]);

// Use the casters
try {
    $int = $provider->provide('int')->cast('123');    // 123 (int)
    $bool = $provider->provide('boolean')->cast('1'); // true (bool)
    $str = $provider->provide('string')->cast(123);   // "123" (string)
} catch (CastableException $e) {
    // Handle conversion errors
    echo "Conversion error: " . $e->getMessage();
}
```

## Built-in Casters

The library includes the following built-in casters:

| Caster Name | Class | Description |
|-------------|-------|-------------|
| `int` | `IntCaster` | Converts values to integers. Accepts strings and floating-point numbers. |
| `float` | `FloatCaster` | Converts values to floating-point numbers. Accepts strings and integers. |
| `string` | `StringCaster` | Converts values to strings. Supports scalar types, arrays (to JSON), objects with `__toString()`, and other types. |
| `boolean` | `BooleanCaster` | Converts values to booleans. Correctly handles strings like "true", "false", "yes", "y", "n" "no", and numeric values. |
| `array` | `ArrayCaster` | Converts values to arrays. Transforms JSON strings, objects, iterable structures. |
| `json` | `JsonCaster` | Converts values to JSON strings. Supports arrays, objects, and other serializable types. |
| `timestamp` | `TimestampCaster` | Converts date values to Unix timestamp. Accepts date strings, DateTime objects, and numbers. |
| `carbon` | `CarbonCaster` | Converts date values to Carbon objects. Accepts strings, timestamps, and DateTime objects. |
| `email` | `EmailCaster` | Validates and normalizes email addresses. Returns an Email object. |
| `uuid` | `UuidCaster` | Works with UUIDs. Converts strings to UUID objects with validation. |
| `slug` | `SlugCaster` | Converts strings to URL-friendly format (slug). Removes special characters, replaces spaces with hyphens. |
| `base64` | `Base64Caster` | Encodes strings to base64 format. |
| `byte` | `ByteCaster` | Converts various byte representations (KB, MB, GB) to a unified Byte object. |
| `now` | `NowCaster` | Generates the current date and time as a Carbon object. Ignores input value. |

## Additional Casters

The following casters are available through separate packages:

### IP Addresses (package `bermudaphp/cast-ip`)

```bash
composer require bermudaphp/cast-ip
```

| Caster Name | Class | Description |
|-------------|-------|-------------|
| `ip` | `IpAddressCaster` | Converts values to IpAddress objects. Supports strings, integers (for IPv4), and arrays with an 'address' field. |
| `ipv4` | `IpAddressCaster(IpVersion::IPv4)` | Specialized caster for working only with IPv4 addresses. Validates version compatibility. |
| `ipv6` | `IpAddressCaster(IpVersion::IPv6)` | Specialized caster for working only with IPv6 addresses. Validates version compatibility. |

### Phone Numbers (package `bermudaphp/cast-phone`)

```bash
composer require bermudaphp/cast-phone
```

| Caster Name | Class | Description |
|-------------|-------|-------------|
| `phone` | `PhoneCaster` | Validates and formats phone numbers. Returns a Phone object with full support for international formatting and validation. |

### PHP 8.1+ Enumerations (Enums)

The library provides an abstract class `EnumCasterAbstract` for creating casters that work with PHP 8.1+ enumerations:

```php
<?php

namespace App\Casters;

use Bermuda\Caster\EnumCasterAbstract;

// Let's say we have a Status enumeration
enum Status
{
    case ACTIVE;
    case INACTIVE;
    case PENDING;
}

// Create a caster for this enumeration
class StatusCaster extends EnumCasterAbstract
{
    protected function enumClass(): string
    {
        return Status::class;
    }
    
    public function getName(): string
    {
        return 'status';
    }
}
```

For backed enums with specified values:

```php
<?php

enum PaymentStatus: string
{
    case PAID = 'paid';
    case PENDING = 'pending';
    case FAILED = 'failed';
}

class PaymentStatusCaster extends EnumCasterAbstract
{
    protected function enumClass(): string
    {
        return PaymentStatus::class;
    }
    
    public function getName(): string
    {
        return 'payment_status';
    }
}

// Usage
$caster = new PaymentStatusCaster();
$status = $caster->cast('paid'); // PaymentStatus::PAID
```

## Creating a Custom Caster

Creating your own casters is straightforward:

```php
<?php

namespace App\Casters;

use Bermuda\Caster\CasterInterface;
use Bermuda\Caster\CastableException;

class CustomTypeCaster implements CasterInterface
{
    public function cast(mixed $value): CustomType
    {
        if ($value instanceof CustomType) {
            return $value;
        }
        
        try {
            // Conversion logic
            return new CustomType($value);
        } catch (\Throwable $e) {
            throw CastableException::fromPrevious($e, $this, $value);
        }
    }

    public function getName(): string
    {
        return 'custom_type';
    }
}
```

## Caster Composition

You can combine casters using `PipeCaster` or the `|` syntax:

```php
// Create a composite caster manually
$jsonArrayCaster = new PipeCaster([
    $provider->provide('string'),
    $provider->provide('json'),
    $provider->provide('array')
]);

// Or through provider syntax
$jsonArrayCaster = $provider->provide('string|json|array');

// Sequential application of casters
$result = $jsonArrayCaster->cast('{"key":"value"}'); // ['key' => 'value']
```

## Error Handling

The library uses specialized exceptions for error handling:

```php
try {
    $caster->cast($invalidValue);
} catch (CastableException $e) {
    echo "Error: " . $e->getMessage();
    echo "Caster: " . $e->caster->getName();
    echo "Original value: " . print_r($e->value, true);
}
```

## Requirements

- PHP 8.4 or higher

## License

MIT

---

For more detailed information, refer to the [code documentation](./src/) and [tests](./tests/).
