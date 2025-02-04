<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Actions\CreateNewAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewAccountRequest;
use App\Http\Resources\CreateNewAccountResource;
use Illuminate\Http\JsonResponse;

final class AccountController extends Controller
{
    public function store(CreateNewAccountRequest $request, CreateNewAccount $action): JsonResponse
    {
        /** @noinspection SpellCheckingInspection */
        return new CreateNewAccountResource(
            $action->handle([
                'number' => $request->integer('numero_conta'),
                'balance' => $request->float('saldo'),
            ]),
        )->response()->setStatusCode(201);
    }
}
