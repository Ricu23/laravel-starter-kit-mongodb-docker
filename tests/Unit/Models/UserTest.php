<?php

declare(strict_types=1);

use App\Models\User;

test('user model has connection', function (): void {
    expect(User::class)->toHaveMethod('getConnectionName');
    expect(new User()->getConnectionName())->toBe('mongodb');
});

test('user model has collection', function (): void {
    expect(new User()->getTable())->toBe('users');
});
