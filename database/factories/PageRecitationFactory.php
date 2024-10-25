<?php

namespace Database\Factories;

use App\Models\Mosque;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PageRecitation>
 */
class PageRecitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->numberBetween(1, 604);
        $end = fake()->numberBetween($start, 604);

        return [
            //
            'start_page' => $start,
            'end_page' => $end,
            'mosque_id' => Mosque::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'rate' => 'good',
        ];
    }
}
