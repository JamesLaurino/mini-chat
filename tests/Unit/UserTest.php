<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('test create a new user', function () {
    // GIVEN
    User::factory()->create([
        'name' => 'Alice',
    ]);

    // WHEN
    $user = User::where('name', 'Alice')->exists();

    // THEN
    expect($user)->toBeTrue();
});

