<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{

    public function definition()
    {
        return [
            'about' => fake()->text(50),
            'instruction' => fake()->text(50),
            'behaviour' => fake()->text(50),
        ];
    }
}
