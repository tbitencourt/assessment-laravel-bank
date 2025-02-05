<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
final class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var PaymentType $paymentType */
        $paymentType = fake()->randomElement(PaymentType::cases());

        return [
            'account_id' => Account::factory(),
            'payment_type' => $paymentType,
            'fee' => $paymentType->fee(),
            'value' => (fake()->randomFloat(2, 0, 100) * 100),
        ];
    }
}
