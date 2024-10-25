<?php

namespace Database\Factories;

use App\Models\Mosque;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Point>
 */
class PointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory()->create()->id,
            'points' => fake()->numberBetween(-100, 100),
            'reason' => fake()->realText(),
            'mosque_id' => Mosque::factory()->create()->id,
        ];
    }
}
