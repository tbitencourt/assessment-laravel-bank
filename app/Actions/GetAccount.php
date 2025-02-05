<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Account;

final class GetAccount
{
    public function handle(int $number): Account
    {
        return Account::query()->where('number', $number)->firstOrFail();
    }
}
