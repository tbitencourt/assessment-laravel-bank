<?php

declare(strict_types=1);

use App\Http\Actions\CreateNewAccount;
use App\Models\Account;

it('creates a new account', function () {
    // arrange...
    $expected = [
        'number' => 234,
        'balance' => 180.37,
    ];
    /** @var CreateNewAccount $action */
    $action = app(CreateNewAccount::class);

    // act...
    $account = $action->handle($expected);

    // assert...
    expect(Account::query()->count())->toBe(1)
        ->and($account)->toBeInstanceOf(Account::class)
        ->and($account->number)->toBe($expected['number'])
        ->and($account->balance / 100)->toBe($expected['balance']);
});
