# laravel-utils

A collection of commonly used Laravel utilities, traits, and helpers.

## Requirements

- PHP `^8.3`
- Laravel `^11.0 || ^12.0 || ^13.0`

## Installation

```bash
composer require pentacore/laravel-utils
```

The service provider is auto-discovered.

## Utilities

### `EnumUtils` trait

Adds a set of helpers to any backed enum.

```php
use Pentacore\LaravelUtils\Concerns\EnumUtils;

enum Status: string
{
    use EnumUtils;

    case Active = 'active';
    case Pending = 'pending';
    case Archived = 'archived';
}
```

| Method                                | Description                                                  |
| ------------------------------------- | ------------------------------------------------------------ |
| `Status::names()`                     | Array of case names.                                         |
| `Status::values()`                    | Array of backing values.                                     |
| `Status::array()`                     | Associative array `[value => name]`.                         |
| `Status::validationRule()`            | `Rule::in(values)` for use in form requests / validators.    |
| `Status::iterator()`                  | `Generator` keyed by case name.                              |
| `Status::mapForSelect($withNull, $labelKey)` | Sorted `[label, value]` list for select dropdowns.    |
| `Status::commaSeparatedValues()`      | Comma-separated string of values.                            |
| `$case->asSlug()`                     | URL-friendly slug of the value.                              |
| `$case->toString()`                   | Value cast to string.                                        |
| `$case->equals($value)`               | Strict comparison of value.                                  |

If a case implements a `label()` method, `mapForSelect()` will use it instead of the case name.

## Testing

```bash
composer test
```

## License

MIT
