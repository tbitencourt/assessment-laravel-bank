<?php

declare(strict_types=1);

use App\Models\Account;

it('can create an activity', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $expected = [
        'numero_conta' => 234,
        'saldo' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $expected);

    // Assert...
    $account = Account::query()->first();
    $response->assertStatus(201);
    /** @noinspection SpellCheckingInspection */
    expect(Account::query()->count())->toBe(1)
        ->and($account)->toBeInstanceOf(Account::class)
        ->and($account->number)->toBe($expected['numero_conta'])
        ->and($account->balance / 100)->toBe($expected['saldo']);
});

it('does not handle empty request', function () {
    // Arrange...
    $expected = [];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $expected);

    // Assert...
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.required', ['attribute' => __('validation.attributes.numero_conta')]),
        'saldo' => __('validation.required', ['attribute' => __('validation.attributes.saldo')]),
    ]);
    $account = Account::query()->first();
    expect($account)->toBeNull();
});

it('does not handle not numeric fields on request', function () {
    // Arrange...
    $expected = [
        'numero_conta' => '234A',
        'saldo' => '180.37B',
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $expected);

    // Assert...
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.numeric', ['attribute' => __('validation.attributes.numero_conta')]),
        'saldo' => __('validation.numeric', ['attribute' => __('validation.attributes.saldo')]),
    ]);
    expect(Account::query()->first())->toBeNull();
});

it('does not handle an existing account number', function () {
    // Arrange...
    /** @var Account $previousAccount */
    $previousAccount = Account::factory(['number' => 234])->create();
    /** @noinspection SpellCheckingInspection */
    $expected = [
        'numero_conta' => 234,
        'saldo' => 180.37,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $expected);

    // Assert...
    $response->assertStatus(422)->assertJsonValidationErrors([
        'numero_conta' => __('validation.unique', ['attribute' => __('validation.attributes.numero_conta')]),
    ]);
    expect(Account::query()->count())->toBe(1);
});

it('does not handle not decimal balance', function () {
    // Arrange...
    /** @noinspection SpellCheckingInspection */
    $expected = [
        'numero_conta' => 234,
        'saldo' => 180,
    ];

    // Act...
    $response = $this->postJson(route('api.v1.accounts.store'), $expected);

    // Assert...
    $response->assertStatus(422)->assertJsonValidationErrors([
        'saldo' => __('validation.decimal', ['attribute' => __('validation.attributes.saldo'), 'decimal' => '2']),
    ]);
    expect(Account::query()->first())->toBeNull();
});
