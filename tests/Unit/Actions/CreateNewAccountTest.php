<?php

declare(strict_types=1);

use App\Actions\CreateNewAccount;
use App\Models\Account;

it('creates a new account', function () {
    // arrange...
    $accountData = Account::factory()->make([
        'number' => 234,
        'balance' => 180.37,
    ]);

    /** @var CreateNewAccount $action */
    $action = app(CreateNewAccount::class);

    // act...
    $newAccount = $action->handle($accountData->number, $accountData->balance);

    // assert...
    expect(Account::query()->count())->toBe(1)
        ->and($newAccount)->toBeInstanceOf(Account::class)
        ->and($newAccount->number)->toBe($accountData->number)
        ->and($newAccount->balance / 100)->toBe($accountData->balance);
});
