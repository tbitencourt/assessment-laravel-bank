<?php

declare(strict_types=1);

use App\Models\Transaction;

test('to array', function () {
    $transaction = Transaction::factory()->create()->refresh();

    expect(array_keys($transaction->toArray()))
        ->toBe([
            'id',
            'account_id',
            'payment_type',
            'fee',
            'value',
            'created_at',
            'updated_at',
        ]);
});
