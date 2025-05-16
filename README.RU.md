# Bermuda Cast - Библиотека преобразования типов

*Read this in [English](README.md)*

`bermudaphp/cast` - это легковесная и мощная библиотека для преобразования типов в PHP-приложениях. Она обеспечивает надежное и гибкое преобразование данных между различными типами с возможностью расширения.

## Установка

```bash
composer require bermudaphp/cast
```

## Основные возможности

- 🔄 **Преобразование типов** - безопасное и предсказуемое преобразование между различными типами данных
- 🧩 **Расширяемость** - легкое создание собственных кастеров для пользовательских типов
- 🔌 **Централизованное управление** - единый реестр для всех преобразователей
- 🛡️ **Валидация** - встроенная валидация и обработка ошибок преобразования
- 📦 **Композиция** - возможность объединения кастеров для сложных преобразований

## Быстрый старт

```php
<?php

use Bermuda\Caster\CasterProvider;
use Bermuda\Caster\IntCaster;
use Bermuda\Caster\StringCaster;
use Bermuda\Caster\BooleanCaster;
use Bermuda\Caster\CastableException;

// Создание провайдера кастеров
$provider = new CasterProvider([
    new IntCaster(),
    new StringCaster(),
    new BooleanCaster(),
]);

// Использование кастеров
try {
    $int = $provider->provide('int')->cast('123');    // 123 (int)
    $bool = $provider->provide('boolean')->cast('1'); // true (bool)
    $str = $provider->provide('string')->cast(123);   // "123" (string)
} catch (CastableException $e) {
    // Обработка ошибок преобразования
    echo "Ошибка преобразования: " . $e->getMessage();
}
```

## Встроенные кастеры

Библиотека включает следующие встроенные кастеры:

| Имя кастера | Класс | Описание |
|-------------|-------|----------|
| `int` | `IntCaster` | Преобразует значения в целые числа (integer). Принимает строки и числа с плавающей точкой. |
| `float` | `FloatCaster` | Преобразует значения в числа с плавающей точкой (float). Принимает строки и целые числа. |
| `string` | `StringCaster` | Преобразует значения в строки. Поддерживает скалярные типы, массивы (в JSON), объекты со `__toString()` и другие типы. |
| `boolean` | `BooleanCaster` | Преобразует значения в логические (bool). Корректно обрабатывает строки "true", "false", "y", "n", "yes", "no", и числовые значения. |
| `array` | `ArrayCaster` | Преобразует значения в массивы. Преобразует JSON-строки, объекты, итерируемые структуры. |
| `json` | `JsonCaster` | Преобразует значения в JSON-строки. Поддерживает массивы, объекты и другие сериализуемые типы. |
| `timestamp` | `TimestampCaster` | Преобразует значения дат в Unix-timestamp. Принимает строки с датами, объекты DateTime и числа. |
| `carbon` | `CarbonCaster` | Преобразует значения дат в объекты Carbon. Принимает строки, timestamp, и объекты DateTime. |
| `email` | `EmailCaster` | Валидирует и нормализует email-адреса. Возвращает объект Email. |
| `uuid` | `UuidCaster` | Работает с UUID. Преобразует строки в объекты UUID с валидацией. |
| `slug` | `SlugCaster` | Преобразует строки в URL-дружественный формат (slug). Удаляет специальные символы, заменяет пробелы на дефисы. |
| `base64` | `Base64Caster` | Кодирует строки в base64-формат. |
| `byte` | `ByteCaster` | Преобразует различные представления байтов (KB, MB, GB) в единый объект Byte. |
| `now` | `NowCaster` | Генерирует текущую дату и время как объект Carbon. Игнорирует входное значение. |

## Дополнительные кастеры

Следующие кастеры доступны через отдельные пакеты:

### IP-адреса (пакет `bermudaphp/cast-ip`)

```bash
composer require bermudaphp/cast-ip
```

| Имя кастера | Класс | Описание |
|-------------|-------|----------|
| `ip` | `IpAddressCaster` | Преобразует значения в объекты IpAddress. Поддерживает строки, целые числа (для IPv4) и массивы с полем 'address'. |
| `ipv4` | `IpAddressCaster(IpVersion::IPv4)` | Специализированный кастер для работы только с IPv4-адресами. Проверяет соответствие версии. |
| `ipv6` | `IpAddressCaster(IpVersion::IPv6)` | Специализированный кастер для работы только с IPv6-адресами. Проверяет соответствие версии. |

### Телефонные номера (пакет `bermudaphp/cast-phone`)

```bash
composer require bermudaphp/cast-phone
```

| Имя кастера | Класс | Описание |
|-------------|-------|----------|
| `phone` | `PhoneCaster` | Валидирует и форматирует телефонные номера. Возвращает объект Phone с полной поддержкой международного форматирования и валидации. |

### Перечисления

Библиотека предоставляет абстрактный класс `EnumCasterAbstract` для создания кастеров, работающих с перечислениями (enums):

```php
<?php

namespace App\Casters;

use Bermuda\Caster\EnumCasterAbstract;

// Предположим, у нас есть перечисление Status
enum Status
{
    case ACTIVE;
    case INACTIVE;
    case PENDING;
}

// Создаем кастер для этого перечисления
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

Для бэкед-перечислений (backed enums) с указанием значений:

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

// Использование
$caster = new PaymentStatusCaster();
$status = $caster->cast('paid'); // PaymentStatus::PAID
```

## Создание собственного кастера

Создавать свои кастеры очень просто:

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
            // Логика преобразования
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

## Композиция кастеров

Вы можете объединять кастеры с помощью `PipeCaster` или синтаксиса `|`:

```php
// Создание композитного кастера вручную
$jsonArrayCaster = new PipeCaster([
    $provider->provide('string'),
    $provider->provide('json'),
    $provider->provide('array')
]);

// Или через синтаксис провайдера
$jsonArrayCaster = $provider->provide('string|json|array');

// Последовательное применение кастеров
$result = $jsonArrayCaster->cast('{"key":"value"}'); // ['key' => 'value']
```

## Обработка ошибок

Библиотека использует специализированные исключения для обработки ошибок:

```php
try {
    $caster->cast($invalidValue);
} catch (CastableException $e) {
    echo "Ошибка: " . $e->getMessage();
    echo "Кастер: " . $e->caster->getName();
    echo "Исходное значение: " . print_r($e->value, true);
}
```

## Требования

- PHP 8.4 или выше

## Лицензия

MIT

---

Для получения более подробной информации обратитесь к [документации в коде](./src/) и [тестам](./tests/).
