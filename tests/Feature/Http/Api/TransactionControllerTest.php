<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\Transaction;

it('can create a debit transaction', function () {
    // Arrange...
    $account = Account::factory()->create(['number' => 234, 'balance' => 18037]);
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'D',
        'numero_conta' => $account->number,
        'valor' => 10,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    $transaction = Transaction::query()->first();

    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(201)->assertExactJson([
        'numero_conta' => $input['numero_conta'],
        'saldo' => 170.07,
    ]);

    /** @noinspection SpellCheckingInspection */
    expect(Transaction::query()->count())->toBe(1)
        ->and($transaction)->toBeInstanceOf(Transaction::class)
        ->and($transaction->account->number)->toBe(234)
        ->and($transaction->account->balance)->toBe(17007)
        ->and($transaction->payment_type->value)->toBe('D')
        ->and($transaction->value)->toBe(1000);
});

it('can create a credit transaction', function () {
    // Arrange...
    $account = Account::factory()->create(['number' => 234, 'balance' => 18037]);
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'C',
        'numero_conta' => $account->number,
        'valor' => 10,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    $transaction = Transaction::query()->first();

    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(201)->assertExactJson([
        'numero_conta' => $input['numero_conta'],
        'saldo' => 169.87,
    ]);

    /** @noinspection SpellCheckingInspection */
    expect(Transaction::query()->count())->toBe(1)
        ->and($transaction)->toBeInstanceOf(Transaction::class)
        ->and($transaction->account->number)->toBe(234)
        ->and($transaction->account->balance)->toBe(16987)
        ->and($transaction->payment_type->value)->toBe('C')
        ->and($transaction->value)->toBe(1000);
});

it('can create a pix transaction', function () {
    // Arrange...
    $account = Account::factory()->create(['number' => 234, 'balance' => 18037]);
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'P',
        'numero_conta' => $account->number,
        'valor' => 10,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    $transaction = Transaction::query()->first();

    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(201)->assertExactJson([
        'numero_conta' => $input['numero_conta'],
        'saldo' => 170.37,
    ]);

    /** @noinspection SpellCheckingInspection */
    expect(Transaction::query()->count())->toBe(1)
        ->and($transaction)->toBeInstanceOf(Transaction::class)
        ->and($transaction->account->number)->toBe(234)
        ->and($transaction->account->balance)->toBe(17037)
        ->and($transaction->payment_type->value)->toBe('P')
        ->and($transaction->value)->toBe(1000);
});

it('does not handle empty request', function () {
    // Arrange...
    $input = [];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'forma_pagamento' => __('validation.required', ['attribute' => __('validation.attributes.forma_pagamento')]),
        'numero_conta' => __('validation.required', ['attribute' => __('validation.attributes.numero_conta')]),
        'valor' => __('validation.required', ['attribute' => __('validation.attributes.valor')]),
    ]);
    $transaction = Transaction::query()->first();
    expect($transaction)->toBeNull();
});

it('does not handle not numeric fields on request', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'D',
        'numero_conta' => '234A',
        'valor' => '180.37B',
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.numeric', ['attribute' => __('validation.attributes.numero_conta')]),
        'valor' => __('validation.numeric', ['attribute' => __('validation.attributes.valor')]),
    ]);
    expect(Transaction::query()->first())->toBeNull();
});

it('does not handle an invalid payment type', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'X',
        'numero_conta' => 234,
        'value' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'forma_pagamento' => __('validation.enum', ['attribute' => __('validation.attributes.forma_pagamento')]),
    ]);
    expect(Transaction::query()->first())->toBeNull();
});

it('does not handle a not found account number', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'D',
        'numero_conta' => 234,
        'valor' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.exists', ['attribute' => __('validation.attributes.numero_conta')]),
    ]);
    expect(Transaction::query()->count())->toBe(0);
});

it('does not handle more than 2 decimal balance', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'D',
        'numero_conta' => 234,
        'valor' => 180.123,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'valor' => __(
            'validation.decimal',
            ['attribute' => __('validation.attributes.valor'), 'decimal' => '0-2']
        ),
    ]);
    expect(Transaction::query()->first())->toBeNull();
});

test('if value is higher then account balance', function () {
    // Arrange...
    $account = Account::factory()->create(['number' => 234, 'balance' => 100]);
    /** @noinspection SpellCheckingInspection */
    $input = [
        'forma_pagamento' => 'P',
        'numero_conta' => $account->number,
        'valor' => 1000,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.transactions.store'), $input);

    // Assert...
    $response->assertNotFound()->assertExactJson([
        'message' => __('exceptions.insufficient_funds_message'),
    ]);
    expect(Transaction::query()->first())->toBeNull();
});
