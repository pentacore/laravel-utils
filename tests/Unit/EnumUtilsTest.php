<?php

declare(strict_types=1);

use Illuminate\Validation\Rules\In;
use Workbench\App\Concerns\Direction;
use Workbench\App\Concerns\Priority;
use Workbench\App\Concerns\Status;

it('returns case names', function (): void {
    expect(Status::names())->toBe(['Active', 'Pending_Review', 'Archived']);
});

it('returns case values', function (): void {
    expect(Status::values())->toBe(['active', 'pending_review', 'archived']);
    expect(Priority::values())->toBe([1, 2, 3]);
});

it('returns array of value => name', function (): void {
    expect(Status::array())->toBe([
        'active' => 'Active',
        'pending_review' => 'Pending_Review',
        'archived' => 'Archived',
    ]);
});

it('builds an In validation rule from values', function (): void {
    $in = Status::validationRule();

    expect($in)->toBeInstanceOf(In::class);
    expect((string) $in)->toContain('active', 'pending_review', 'archived');
});

it('iterates all cases keyed by name', function (): void {
    $items = iterator_to_array(Status::iterator());

    expect($items)->toHaveKeys(['Active', 'Pending_Review', 'Archived']);
    expect($items['Active'])->toBe(Status::Active);
});

it('converts a string-backed value to a slug', function (): void {
    expect(Status::Pending_Review->asSlug())->toBe('pending-review');
    expect(Status::Pending_Review->asSlug('_'))->toBe('pending_review');
});

it('builds a select map sorted by label without null option', function (): void {
    $map = Status::mapForSelect();

    expect($map)->toHaveCount(3);
    expect($map[0]['name'])->toBe('Active');
    expect($map[0]['value'])->toBe('active');
    expect($map[1]['name'])->toBe('Archived');
    expect($map[2]['name'])->toBe('Pending Review');
});

it('prepends a null option when requested', function (): void {
    $map = Status::mapForSelect(withNull: true);

    expect($map[0])->toBe(['name' => 'None', 'value' => null]);
    expect($map)->toHaveCount(4);
});

it('uses a custom label key', function (): void {
    $map = Status::mapForSelect(labelKey: 'label');

    expect($map[0])->toHaveKeys(['label', 'value']);
    expect($map[0]['label'])->toBe('Active');
});

it('uses the case label() method when defined', function (): void {
    $map = Priority::mapForSelect();

    expect(array_column($map, 'name'))->toBe([
        'High Priority',
        'Low Priority',
        'Medium Priority',
    ]);
});

it('returns comma-separated values', function (): void {
    expect(Status::commaSeparatedValues())->toBe('active,pending_review,archived');
    expect(Priority::commaSeparatedValues())->toBe('1,2,3');
});

it('casts the case to a string', function (): void {
    expect(Status::Active->toString())->toBe('active');
    expect(Priority::High->toString())->toBe('3');
});

it('compares values with equals()', function (): void {
    expect(Status::Active->equals('active'))->toBeTrue();
    expect(Status::Active->equals('archived'))->toBeFalse();
    expect(Priority::High->equals(3))->toBeTrue();
    expect(Priority::High->equals('3'))->toBeFalse();
});

describe('pure (non-backed) enums', function (): void {
    it('returns case names', function (): void {
        expect(Direction::names())->toBe(['North', 'South', 'East', 'West', 'North_East']);
    });

    it('falls back to case names for values()', function (): void {
        expect(Direction::values())->toBe(['North', 'South', 'East', 'West', 'North_East']);
    });

    it('builds a name => name map for array()', function (): void {
        expect(Direction::array())->toBe([
            'North' => 'North',
            'South' => 'South',
            'East' => 'East',
            'West' => 'West',
            'North_East' => 'North_East',
        ]);
    });

    it('builds an In validation rule from case names', function (): void {
        $rule = Direction::validationRule();

        expect($rule)->toBeInstanceOf(In::class);
        expect((string) $rule)->toContain('North', 'South', 'North_East');
    });

    it('iterates all cases keyed by name', function (): void {
        $items = iterator_to_array(Direction::iterator());

        expect($items)->toHaveKeys(['North', 'South', 'East', 'West', 'North_East']);
        expect($items['North'])->toBe(Direction::North);
    });

    it('slugifies the case name', function (): void {
        expect(Direction::North_East->asSlug())->toBe('north-east');
        expect(Direction::North_East->asSlug('_'))->toBe('north_east');
    });

    it('builds a select map using case names as values', function (): void {
        $map = Direction::mapForSelect();

        expect($map)->toHaveCount(5);
        expect($map[0])->toBe(['name' => 'East', 'value' => 'East']);
        expect(array_column($map, 'name'))->toBe(['East', 'North', 'North East', 'South', 'West']);
    });

    it('returns comma-separated case names', function (): void {
        expect(Direction::commaSeparatedValues())->toBe('North,South,East,West,North_East');
    });

    it('casts the case to its name', function (): void {
        expect(Direction::North->toString())->toBe('North');
        expect(Direction::North_East->toString())->toBe('North_East');
    });

    it('compares against the case name with equals()', function (): void {
        expect(Direction::North->equals('North'))->toBeTrue();
        expect(Direction::North->equals('South'))->toBeFalse();
    });
});
