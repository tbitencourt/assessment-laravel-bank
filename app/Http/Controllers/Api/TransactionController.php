<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateNewTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewTransactionRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Http\JsonResponse;

final class TransactionController extends Controller
{
    public function store(CreateNewTransactionRequest $request, CreateNewTransaction $action): JsonResponse
    {
        /** @noinspection SpellCheckingInspection */
        return new AccountResource(
            $action->handle(
                paymentType: (string) $request->string('forma_pagamento'),
                number: $request->integer('numero_conta'),
                value: $request->float('valor'),
            )->account,
        )->response()->setStatusCode(201);
    }
}
