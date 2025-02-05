<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class CreateNewTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var Transaction $transaction */
        $transaction = $this->resource;

        /** @noinspection SpellCheckingInspection */
        return [
            'numero_conta' => $transaction->account->number,
            'saldo' => ($transaction->account->balance / 100),
        ];
    }
}
