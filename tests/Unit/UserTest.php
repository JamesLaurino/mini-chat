<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('crée un utilisateur', function () {
    User::factory()->create([
        'name' => 'Alice',
    ]);

    expect(User::where('name', 'Alice')->exists())->toBeTrue();
});

