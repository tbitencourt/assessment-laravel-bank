<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Models\Account;

final class CreateNewAccount
{
    /**
     * @param  array{number: int, balance: float}  $data
     */
    public function handle(array $data): Account
    {
        return Account::query()->create([
            'number' => $data['number'],
            'balance' => $data['balance'] * 100,
        ]);
    }
}
