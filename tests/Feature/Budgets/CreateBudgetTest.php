<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('validates required fields when creating a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budget.create'))->post(route('budget.store'), [
        'name' => '',
        'amount' => '',
        'type' => ''
    ]);

    $response->assertRedirect(route('budget.create'));

    $response->assertSessionHasErrors([
        'name',
        'amount',
        'type'
    ]);
});

test('does not allow guest to create budget', function() {
    $response = $this->post(route('budget.store'), [
        'name' => 'Boda',
        'amount' => 1000,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('login'));
});

test('assigns the created budget to the authenticated user', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $this->actingAs($user)->post(route('budget.store'), [
        'name' => 'Viaje a Las Vegas',
        'amount' => 1000,
        'type' => 'goal'
    ]);

    $this->assertDatabaseHas('budgets', [
        'name' => 'Viaje a Las Vegas',
        'amount' => 1000,
        'type' => 'goal',
        'user_id' => $user->id
    ]);
    
    $budget = Budget::first();

    expect($budget->user_id)->toBe($user->id);
});

test('creates a budget and redirects with success message', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->post(route('budget.store'), [
        'name' => 'Viaje a Las Vegas',
        'amount' => 1000,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('dashboard'));

    $response->assertSessionHas('success', 'Presupuesto creado correctamente');
});

test('does not allow unverified users to create budgets', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->post(route('budget.store'), [
        'name' => 'Viaje a Las Vegas',
        'amount' => 1000,
        'type' => 'goal'
    ]);
    
    $response->assertRedirect(route('verification.notice'));
});

test('validates amount must be greater than zero', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budget.create'))->post(route('budget.store'), [
        'name' => 'Boda',
        'amount' => 0,
        'type' => 'general'
    ]);

    $response->assertRedirect(route('budget.create'));

    $response->assertSessionHasErrors(['amount']);
});

test('validates type must be valid', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budget.create'))->post(route('budget.store'), [
        'name' => 'Boda',
        'amount' => 100,
        'type' => 'not_valid'
    ]);

    $response->assertRedirect(route('budget.create'));

    $response->assertSessionHasErrors(['type']);
});

test('accept valid budget types', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->post(route('budget.store'), [
        'name' => 'Boda',
        'amount' => 100,
        'type' => 'general'
    ]);

    $response->assertRedirect(route('dashboard'));

    $response->assertSessionHas('success', 'Presupuesto creado correctamente');

    $this->assertDatabaseHas('budgets', ['type' => 'general']);
});