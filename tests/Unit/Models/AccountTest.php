<?php

declare(strict_types=1);

use App\Models\Account;

test('to array', function () {
    $account = Account::factory()->create()->refresh();

    expect(array_keys($account->toArray()))
        ->toBe([
            'id',
            'number',
            'balance',
            'created_at',
            'updated_at',
        ]);
});
