<?php

namespace Database\Factories;

use App\Models\Mosque;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionRecitation>
 */
class SectionRecitationFactory extends Factory
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
            'section_id' => fake()->numberBetween(1, 30),
            'rate' => 'good',
            'mosque_id' => Mosque::factory()->create()->id,
        ];
    }
}
