<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComplianceFeature>
 */
class ComplianceFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->word()),
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'is_enabled' => fake()->boolean(),
            'module_name' => fake()->optional()->word(),
            'metadata' => null,
        ];
    }
}
