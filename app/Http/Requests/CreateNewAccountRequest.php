<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CreateNewAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        /** @noinspection SpellCheckingInspection */
        return [
            'numero_conta' => 'required|numeric|unique:accounts,number',
            'saldo' => 'required|numeric|decimal:0,2|min:0.00',
        ];
    }
}
