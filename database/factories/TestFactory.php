<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    protected $model = Test::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'time_limit' => $this->faker->numberBetween(10, 60),
            'passing_score' => $this->faker->numberBetween(60, 90),
            'is_published' => $this->faker->boolean(80),
        ];
    }
}
