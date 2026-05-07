<?php

declare(strict_types=1);

namespace Pentacore\LaravelUtils\Concerns;

use BackedEnum;
use Generator;
use Illuminate\Support\Stringable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use UnitEnum;

trait EnumUtils
{
    /**
     * Resolves a case to its backing value, or its name when the enum is pure.
     */
    private static function resolveValue(UnitEnum $unitEnum): int|string
    {
        return $unitEnum instanceof BackedEnum ? $unitEnum->value : $unitEnum->name;
    }

    /**
     * Returns an array of the enum case names.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Returns an array of the enum values. Pure (non-backed) enums fall back to case names.
     *
     * @return array<int, int|string>
     */
    public static function values(): array
    {
        return array_map(self::resolveValue(...), self::cases());
    }

    /**
     * Returns an associative array of enum values as keys and their corresponding names as values.
     *
     * @return array<int|string, string>
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * Returns a validation rule that checks if a value is one of the enum values.
     */
    public static function validationRule(): In
    {
        return Rule::in(self::values());
    }

    /**
     * @return Generator<string, static>
     */
    public static function iterator(): Generator
    {
        foreach (self::cases() as $case) {
            yield $case->name => $case;
        }
    }

    /**
     * Converts the enum value to a URL-friendly slug. Pure enums slugify the case name.
     */
    public function asSlug(string $separator = '-', string $language = 'en', array $dictionary = ['@' => 'at']): string
    {
        return (new Stringable((string) self::resolveValue($this)))->slug($separator, $language, $dictionary)->toString();
    }

    /**
     * Returns an array of enum values and their corresponding names for use in a select dropdown.
     * If $withNull is true, the array will include a null value at index 0.
     *
     * @return array<int, array{name: string, value: int|string|null}>
     */
    public static function mapForSelect(bool $withNull = false, string $labelKey = 'name'): array
    {
        $arr = [];
        if ($withNull) {
            $arr[] = [$labelKey => 'None', 'value' => null];
        }

        $values = array_map(
            static function ($case) use ($labelKey): array {
                $label = method_exists($case, 'label') ? $case->label() : $case->name;

                return [
                    $labelKey => ucwords(str_replace('_', ' ', $label)),
                    'value' => self::resolveValue($case),
                ];
            },
            self::cases()
        );

        usort(
            $values,
            static fn (array $a, array $b): int => strcmp((string) $a[$labelKey], (string) $b[$labelKey])
        );

        return array_merge($arr, $values);
    }

    /**
     * Returns a comma-separated string of enum values.
     */
    public static function commaSeparatedValues(): string
    {
        return implode(',', self::values());
    }

    /**
     * Returns the enum value as a string. Pure enums return the case name.
     */
    public function toString(): string
    {
        return (string) self::resolveValue($this);
    }

    public function equals(int|string $value): bool
    {
        return self::resolveValue($this) === $value;
    }
}
