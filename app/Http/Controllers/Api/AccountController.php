<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateNewAccount;
use App\Actions\GetAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewAccountRequest;
use App\Http\Requests\GetAccountRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Http\JsonResponse;

final class AccountController extends Controller
{
    public function store(CreateNewAccountRequest $request, CreateNewAccount $action): JsonResponse
    {
        /** @noinspection SpellCheckingInspection */
        return new AccountResource(
            $action->handle(
                number: $request->integer('numero_conta'),
                balance: $request->float('saldo'),
            ),
        )->response()->setStatusCode(201);
    }

    public function show(GetAccountRequest $request, GetAccount $action): JsonResponse
    {
        /** @noinspection SpellCheckingInspection */
        return new AccountResource(
            $action->handle(
                number: $request->integer('numero_conta'),
            ),
        )->response()->setStatusCode(200);
    }
}
