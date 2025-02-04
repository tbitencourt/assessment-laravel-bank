<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class CreateNewAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var Account $account */
        $account = $this->resource;

        /** @noinspection SpellCheckingInspection */
        return [
            'numero_conta' => $account->number,
            'saldo' => ($account->balance / 100),
        ];
    }
}
