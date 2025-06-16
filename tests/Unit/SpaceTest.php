<?php

use App\Models\Space;
use App\Models\User;
use App\Services\SpaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('test create new space', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

    $titre = "un titre de test";

    // WHEN
    $service = app(SpaceService::class);
    $res = $service->createNewSpace($titre);

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res["titre"])->toBe("un titre de test");
    expect($res['user_id'])->toBe($user->id);;
});

it('test get space by user id', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

    Space::factory()->create([
        "titre" => "un titre de test",
        "user_id" => $user->id
    ]);

    Space::factory()->create([
        "titre" => "un titre de test 2",
        "user_id" => $user->id
    ]);

    // WHEN
    $service = app(SpaceService::class);
    $res = $service->getSpaceByUserId();

    // THEN
    expect($res)->toHaveLength(2);
    expect($res[0]["titre"])->toBe("un titre de test");
    expect($res[1]["titre"])->toBe("un titre de test 2");
    expect($res[0]['user_id'])->toBe($user->id);;
});

it('test delete space by id', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

     Space::factory()->create([
        "titre" => "un titre de test",
        "user_id" => $user->id
    ]);

    $spaceTwo = Space::factory()->create([
        "titre" => "un titre de test 2",
        "user_id" => $user->id
    ]);

    // WHEN
    $service = app(SpaceService::class);
    $service->deleteSpaceById($spaceTwo->id);

    $res = Space::all();

    // THEN
    expect($res)->toHaveLength(1);
    expect($res[0]["titre"])->toBe("un titre de test");
    expect($res[0]['user_id'])->toBe($user->id);;
});
