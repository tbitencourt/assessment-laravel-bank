<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentType;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read PaymentType $payment_type
 * @property-read int $fee
 * @property-read int $value
 * @property-read Account $account
 */
final class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'payment_type',
        'fee',
        'value',
    ];

    protected $casts = [
        'payment_type' => PaymentType::class,
    ];

    /**
     * @return BelongsTo<Account, $this>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
