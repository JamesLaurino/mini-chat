<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        /**** USER TEST ****/
        $userTest = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $space1 = Space::factory()->create([
            'titre' => 'Space 1',
            'user_id' => $userTest->id
        ]);

        $space1Bis = Space::factory()->create([
            'titre' => 'Space 1 bis',
            'user_id' => $userTest->id
        ]);

        Conversation::factory()->create([
            'question' => "coucou",
            'response' => "Hello you can i help you Test User?",
            'user_id' => $userTest->id,
            'space_id' => $space1->id
        ]);

        Conversation::factory()->create([
            'question' => "coucou again !",
            'response' => "Hello you can i help you again Test User?",
            'user_id' => $userTest->id,
            'space_id' => $space1Bis->id
        ]);

        Preference::factory()->create([
            'about' => 'About user Test',
            'instruction' => 'Instruction for user Test',
            'behaviour' => 'Behaviour for user Test',
            'user_id' => $userTest->id,
        ]);

        /**** USER ADMIN JAMES ****/
        $userJames = User::factory()->create([
            'name' => 'James',
            'email' => 'james@example.com',
            'role' => UserRole::ADMIN
        ]);


        $space2 = Space::factory()->create([
            'titre' => 'Space 2',
            'user_id' => $userJames->id
        ]);


        Conversation::factory()->create([
            'question' => "coucou",
            'response' => "Hello you can i help you james ?",
            'user_id' => $userJames->id,
            'space_id' => $space2->id
        ]);

        Conversation::factory()->create([
            'question' => "Il fait beau, je suis content de vous voir.",
            'response' => "Oui il fait beau comment puis-je vous aider ?",
            'user_id' => $userJames->id,
            'space_id' => $space2->id
        ]);

        Preference::factory()->create([
            'about' => 'About user James',
            'instruction' => 'Instruction for user James',
            'behaviour' => 'Behaviour for user James',
            'user_id' => $userJames->id,
        ]);
    }
}
