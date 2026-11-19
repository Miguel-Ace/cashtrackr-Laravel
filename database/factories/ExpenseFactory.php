<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'amount' => fake()->numberBetween(1000, 50000),
            'category' => 'other',
            'budget_id' => Budget::factory()
        ];
    }
}
