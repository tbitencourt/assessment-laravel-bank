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
    Route::post('/conta', [AccountController::class, 'store'])
        ->name('api.v1.accounts.store');
    Route::get('/conta', [AccountController::class, 'show'])
        ->name('api.v1.accounts.show');
    /** @noinspection SpellCheckingInspection */
    Route::post('/transacao', [TransactionController::class, 'store'])
        ->name('api.v1.transactions.store');
});
