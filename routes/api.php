<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return ['message' => 'Welcome to the Assessment Laravel Bank API.'];
    })->name('api.v1.index');
    Route::apiResource('conta', AccountController::class)->only(['store'])
        ->names('api.v1.accounts');
    /** @noinspection SpellCheckingInspection */
    Route::apiResource('transacao', TransactionController::class)->only(['store'])
        ->names('api.v1.transactions');
});
