<?php

declare(strict_types=1);

use App\Models\Account;

test('to array', function () {
    $user = Account::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'number',
            'balance',
            'created_at',
            'updated_at',
        ]);
});
