<?php

declare(strict_types=1);

use App\Actions\CreateNewTransaction;
use App\Enums\PaymentType;
use App\Models\Account;
use App\Models\Transaction;

it('creates a new transaction', function () {
    // arrange...
    $existingAccount = Account::factory()->create([
        'number' => 234,
        'balance' => 18037,
    ]);
    $transactionData = Transaction::factory()->make([
        'payment_type' => PaymentType::DebitCard,
        'value' => 10,
    ]);
    /** @var CreateNewTransaction $action */
    $action = app(CreateNewTransaction::class);

    // act...
    $newTransaction = $action->handle(
        $transactionData->payment_type->value,
        $existingAccount->number,
        $transactionData->value
    );

    // assert...
    expect(Transaction::query()->count())->toBe(1)
        ->and($newTransaction)->toBeInstanceOf(Transaction::class)
        ->and($newTransaction->account->number)->toBe(234)
        ->and($newTransaction->account->balance)->toBe(17007)
        ->and($newTransaction->payment_type->value)->toBe('D')
        ->and($newTransaction->value)->toBe(1000);
});
