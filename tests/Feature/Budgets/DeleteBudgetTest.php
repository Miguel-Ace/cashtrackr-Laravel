<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the owner to delete a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete(route('budget.destroy', $budget));

    $response->assertRedirect(route('dashboard'));

    $response->assertSessionHas('success', 'Presupuesto eliminado correctamente');

    $this->assertSoftDeleted('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow guests to delete budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->delete(route('budget.destroy', $budget));

    $response->assertRedirect(route('login'));

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow unverified users to delete budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete(route('budget.destroy', $budget));

    $response->assertRedirect(route('verification.notice'));

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow other users to delete budgets', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create();

    $response = $this->actingAs($otherUser)->delete(route('budget.destroy', $budget));

    $response->assertForbidden();
    $response->assertStatus(403);
    $response->assertSee('No tienes permisos para eliminar este presupuesto');

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});