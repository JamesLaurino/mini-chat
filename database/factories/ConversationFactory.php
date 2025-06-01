<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{

    public function definition()
    {
        return [
            'question' => fake()->text(50),
            'response' => fake()->text(50),
        ];
    }
}
