<?php

use App\Http\Requests\Mocks\MockRequest;
use App\Models\Conversation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('createConversationForNewSpace', function () {

    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    $request = new MockRequest();
    $request->message = "Coucou";
    $response = "bonjour comment allez vous ?";
    $this->actingAs($user);

    // WHEN
    $service = app(\App\Services\ConversationService::class);
    $res = $service->createConversationForNewSpace($request, $response , $space);


    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res->space_id)->toBe($space->id);
    expect($res->question)->toBe($request->message);
    expect($res->response)->toBe($response);
    expect($res->user_id)->toBe($user->id);
});

it('createFirstConversationForNewSpace', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    $this->actingAs($user);

    $request = new MockRequest();
    $request->message = "";

    // WHEN
    $service = app(\App\Services\ConversationService::class);
    $res = $service->createFirstConversationForNewSpace($request, $space);

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res->space_id)->toBe($space->id);
    expect($res->user_id)->toBe($user->id);
});

it('getConversationBySpaceId', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    Conversation::create([
        'question' => "coucou 1",
        'response' => "",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    Conversation::create([
        'question' => "coucou 2",
        'response' => "the only one",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    Conversation::create([
        'question' => "coucou 3",
        'response' => "the second one",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    // WHEN
    $service = app(\App\Services\ConversationService::class);
    $res = $service->getConversationBySpaceId($space->id);

    // THEN
    expect($res)
        ->not->toBeEmpty()
        ->toHaveLength(2);
});

it('getConversationByUserIdAndSpaceId', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);


    Conversation::create([
        'question' => "coucou 2",
        'response' => "the only one",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);


    $this->actingAs($user);

    $request = new MockRequest();
    $request->space_id = $space->id;

    // WHEN
    $service = app(\App\Services\ConversationService::class);
    $res = $service->getConversationByUserIdAndSpaceId($request);


    // THEN
    expect($res[0]->space_id)->toBe($space->id);
    expect($res)->toHaveLength(1);
    expect($res[0]->user_id)->toBe($user->id);
});

it('getConversationForOpenIA', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);


    Conversation::create([
        'question' => "coucou 1",
        'response' => "the only one",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    Conversation::create([
        'question' => "coucou 2",
        'response' => "the only second",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    Conversation::create([
        'question' => "coucou 3",
        'response' => "the only third",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);


    $this->actingAs($user);

    $request = new MockRequest();
    $request->conversationId = $space->id;

    // WHEN
    $service = app(\App\Services\ConversationService::class);
    $res = $service->getConversationForOpenIA($request);

    // THEN
    expect($res["messages"])->toHaveLength(6);
    expect($res)->not()->toBeEmpty();
    expect($res["messages"][0]["content"])->toBe("coucou 1");
});
