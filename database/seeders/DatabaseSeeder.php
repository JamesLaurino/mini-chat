<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Preference;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Conversation::factory(12)->create();
        Space::factory(3)->create();
        Preference::factory(2)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'James',
            'email' => 'james@example.com',
        ]);
    }
}
