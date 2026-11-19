<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the expense owner to update an expense', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = $this->actingAs($user)->put(route('expense.update', [$budget, $expense]), [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('budget.show', $budget));

    $response->assertSessionHas('success','Gasto Actualizado Correctamente');
    
    $this->assertDatabaseHas('expenses', [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);
});

it('does not allow guests to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->put(route('expense.update', [$budget, $expense]), [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('login'));

    $this->assertDatabaseMissing('expenses', [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);
});

it('does not allow unverified users to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->actingAs($user)->put(route('expense.update', [$budget, $expense]), [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $this->assertDatabaseMissing('expenses', [
        'name' => 'Supermercado - Actualizado',
        'amount' => 600,
        'category' => 'food',
    ]);
});

it('does not allow other users to update expenses they do not own', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Original'
    ]);

    $response = $this->actingAs($otherUser)->put(route('expense.update', [$budget, $expense]), [
        'name' => 'Hackeado!!'
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('expenses', [
        'name' => 'Original',
    ]);
});

it('validates required fields when updating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->actingAs($user)->from(route('budget.show', $budget))->put(route('expense.update', [$budget, $expense]), [
        'name' => '',
        'amount' => '',
        'category' => ''
    ]);

    $response->assertRedirect(route('budget.show', $budget));

    $response->assertSessionHasErrors([
        'name',
        'amount',
        'category',
    ]);
});