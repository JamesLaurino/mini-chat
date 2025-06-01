<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    public function definition()
    {
        return [
            'titre' => fake()->name()
        ];
    }
}
