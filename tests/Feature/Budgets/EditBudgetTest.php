<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the owner to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'name' => 'Viaje a las vegas',
        'amount' => 1000,
        'type' => 'general'
    ]);

    $response = $this->actingAs($user)->get(route('budget.edit', $budget));

    $response->assertOk();

    $response->assertSee('Viaje a las vegas');
});

it('does not allow guests to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->get(route('budget.edit', $budget));

    $response->assertRedirect(route('login'));
});

it('does not allow other users to view the edit budget form', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create();

    $response = $this->actingAs($otherUser)->get(route('budget.edit', $budget));

    $response->assertForbidden();
    $response->assertStatus(403);
});