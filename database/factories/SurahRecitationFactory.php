<?php

namespace Database\Factories;

use App\Models\Mosque;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurahRecitation>
 */
class SurahRecitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rate' => 'good',
            'student_id' => Student::factory()->create()->id,
            'surah_id' => fake()->numberBetween(1, 114),
            'mosque_id' => Mosque::factory()->create()->id,
        ];
    }
}
