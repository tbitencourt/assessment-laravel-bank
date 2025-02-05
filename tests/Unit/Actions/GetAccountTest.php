<?php

declare(strict_types=1);

use App\Actions\GetAccount;
use App\Models\Account;

it('finds an account', function () {
    // arrange...
    $existingAccount = Account::factory()->create([
        'number' => 234,
        'balance' => 18037,
    ]);

    /** @var GetAccount $action */
    $action = app(GetAccount::class);

    // act...
    $account = $action->handle($existingAccount->number);

    // assert...
    expect(Account::query()->count())->toBe(1)
        ->and($account)->toBeInstanceOf(Account::class)
        ->and($account->number)->toBe($existingAccount->number)
        ->and($account->balance)->toBe($existingAccount->balance);
});
