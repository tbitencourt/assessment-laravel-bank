<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Account;

final class CreateNewAccount
{
    public function handle(int $number, float $balance): Account
    {
        return Account::query()->create([
            'number' => $number,
            'balance' => (int) ($balance * 100.00),
        ]);
    }
}
