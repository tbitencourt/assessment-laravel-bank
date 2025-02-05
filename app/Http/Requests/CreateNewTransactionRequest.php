<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PaymentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateNewTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'forma_pagamento' => ['required', Rule::enum(PaymentType::class)],
            'numero_conta' => 'required|numeric|exists:accounts,number',
            'valor' => 'required|numeric|decimal:0,2|min:0.00',
        ];
    }
}
