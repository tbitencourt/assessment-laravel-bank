<?php

declare(strict_types=1);

use App\Models\Account;

it('can create an account', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'numero_conta' => 234,
        'saldo' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $input);

    // Assert...
    $account = Account::query()->first();

    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(201)->assertExactJson([
        'numero_conta' => $input['numero_conta'],
        'saldo' => $input['saldo'],
    ]);

    /** @noinspection SpellCheckingInspection */
    expect(Account::query()->count())->toBe(1)
        ->and($account)->toBeInstanceOf(Account::class)
        ->and($account->number)->toBe($input['numero_conta'])
        ->and($account->balance / 100)->toBe($input['saldo']);
});

it('can\'t create with empty request', function () {
    // Arrange...
    $input = [];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.required', ['attribute' => __('validation.attributes.numero_conta')]),
        'saldo' => __('validation.required', ['attribute' => __('validation.attributes.saldo')]),
    ]);
    $account = Account::query()->first();
    expect($account)->toBeNull();
});

it('can\'t create without numeric fields on request', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'numero_conta' => '234A',
        'saldo' => '180.37B',
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.numeric', ['attribute' => __('validation.attributes.numero_conta')]),
        'saldo' => __('validation.numeric', ['attribute' => __('validation.attributes.saldo')]),
    ]);
    expect(Account::query()->first())->toBeNull();
});

it('can\'t create with an existing account number', function () {
    // Arrange...
    $existingAccount = Account::factory()->create(['number' => 234]);
    /** @noinspection SpellCheckingInspection */
    $input = [
        'numero_conta' => $existingAccount->number,
        'saldo' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.unique', ['attribute' => __('validation.attributes.numero_conta')]),
    ]);
    expect(Account::query()->count())->toBe(1);
});

it('can\'t create with more than 2 decimal balance', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $input = [
        'numero_conta' => 234,
        'saldo' => 180.123,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $input);

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'saldo' => __(
            'validation.decimal',
            ['attribute' => __('validation.attributes.saldo'), 'decimal' => '0-2']
        ),
    ]);
    expect(Account::query()->first())->toBeNull();
});

it('can find an account', function () {
    // arrange...
    $existingAccount = Account::factory()->create([
        'number' => 234,
        'balance' => 18037,
    ]);

    // Act...
    /** @noinspection SpellCheckingInspection */
    $response = $this->getJson(route('api.v1.accounts.show', [
        'numero_conta' => $existingAccount->number,
    ]));

    // Assert...
    $account = Account::query()->first();

    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(200)->assertExactJson([
        'numero_conta' => $existingAccount->number,
        'saldo' => $existingAccount->balance / 100,
    ]);

    /** @noinspection SpellCheckingInspection */
    expect(Account::query()->count())->toBe(1)
        ->and($account)->toBeInstanceOf(Account::class)
        ->and($account->number)->toBe($existingAccount->number)
        ->and($account->balance)->toBe($existingAccount->balance);
});

it('can\'t find with empty number', function () {
    // Arrange...
    $input = [];

    // Act...
    $response = $this->getJson(route('api.v1.accounts.show', $input));

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.required', ['attribute' => __('validation.attributes.numero_conta')]),
    ]);
    expect(Account::query()->first())->toBeNull();
});

it('can\' find without numeric fields on request', function () {
    // Arrange...
    $number = '234A';

    // Act...
    /** @noinspection SpellCheckingInspection */
    $response = $this->getJson(route('api.v1.accounts.show', ['numero_conta' => $number]));

    // Assert...
    /** @noinspection SpellCheckingInspection */
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.numeric', ['attribute' => __('validation.attributes.numero_conta')]),
    ]);
    expect(Account::query()->first())->toBeNull();
});

it('can\'t find an not existing account number', function () {
    // Arrange...
    $number = 234;

    // Act...
    /** @noinspection SpellCheckingInspection */
    $response = $this->getJson(route('api.v1.accounts.show', ['numero_conta' => $number]));

    // Assert...
    $response->assertNotFound()->assertExactJson([
        'message' => __('exceptions.not_found_message'),
    ]);
    expect(Account::query()->count())->toBe(0);
});
