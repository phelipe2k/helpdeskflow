<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'in_progress', 'waiting', 'resolved', 'closed']),
            'category_id' => Category::factory(),
            'requester_id' => User::factory(),
            'user_id' => User::factory(),
            'assignee_id' => null,
            'closed_at' => null,
        ];
    }

    /**
     * Indicate that the ticket is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    /**
     * Indicate that the ticket is assigned.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => User::factory(),
            'status' => 'in_progress',
        ]);
    }
}
