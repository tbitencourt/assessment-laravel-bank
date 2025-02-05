<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PaymentType;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateNewTransaction
{
    public function handle(string $paymentType, int $number, float $value): Transaction
    {
        $paymentType = PaymentType::from($paymentType);

        return DB::transaction(fn (): Transaction => $this->createTransaction(
            Account::query()->where('number', $number)->firstOrFail(),
            $paymentType,
            $this->getIntValue($paymentType->fee()),
            $this->getIntValue($value),
        ));
    }

    /**
     * @throws Throwable
     */
    private function createTransaction(Account $account, PaymentType $paymentType, int $fee, int $value): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = $account->transactions()->create([
            'payment_type' => $paymentType,
            'fee' => $fee,
            'value' => $value,
        ]);
        $newBalance = $this->calculateNewBalanceAmount($account->balance, $transaction->value, $transaction->fee);
        throw_if($newBalance < 0, new InsufficientBalanceException());
        $account->update(['balance' => $this->getIntValue($newBalance)]);

        return $transaction->refresh();
    }

    private function calculateNewBalanceAmount(int $balance, int $value, int $fee): float
    {
        return $this->getFloatValue($balance) - ($this->getFloatValue($value) * (1 + $this->getFloatValue($fee)));
    }

    private function getIntValue(float $amount): int
    {
        return (int) ($amount * 100.00);
    }

    private function getFloatValue(int $amount): float
    {
        return (float) $amount / 100.00;
    }
}
