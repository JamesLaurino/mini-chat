<?php

use App\Http\Requests\Mocks\MockRequest;
use App\Models\Conversation;
use App\Models\Space;
use App\Models\User;
use App\Services\ChatService;
use App\Services\ConversationService;
use App\Services\WebService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('getTitleMock', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $this->actingAs($user);

    // WHEN
    $res = (new ChatService())->generateLoremIpsum(1,2);

    // THEN
    expect($res)->not()->toBeEmpty();
});

it('getStreamMock', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    $this->actingAs($user);

     Conversation::create([
        'question' => "coucou 1",
        'response' => "",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    // WHEN
    $response = response()->stream(function () {
        $stream = ["hello ","how ","can ", "i ","help ","you ","today " ,"James ", "?"];
        foreach ($stream as $chunk) {
            echo $chunk;
            usleep(100000);
        }
    });

    ob_start();
    $response->sendContent();
    $output = ob_get_clean();

    // THEN
    expect($output)->toContain('hello')
        ->toContain('James')
        ->toContain('can')
        ->toContain('today')
        ->toContain('?');

});

it('getTitleAPI', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $request = new MockRequest();
    $request->model = "openai/gpt-4.1-mini";
    $request->message = "Bonjour à toi";
    $this->actingAs($user);

    // WHEN
    $service = app(WebService::class);
    $res = $service->getResponse($request, "Génère un titre de maximum 4 mots pour ce message :");

    // THEN
    expect($res)
        ->not()->toBeEmpty()
        ->and(str_word_count($res))->toBeLessThanOrEqual(4)
        ->and(str_word_count($res))->toBeGreaterThanOrEqual(1)
        ->and(str_word_count($res))->toBeLessThanOrEqual(4);
});

it('getStreamAPI', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    $this->actingAs($user);

    $conversation = Conversation::create([
        'question' => "coucou",
        'response' => "Bonjour comment je peux t'aider ?",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    // WHEN
    $request = new MockRequest();
    $request->conversationId = $conversation->id;
    $request->message = "Bonjour il fait beau aujourd'hui !";
    $request->model = "openai/gpt-4.1-mini";

    $serviceConversation = app(ConversationService::class);
    $conversations = $serviceConversation->getConversationForOpenIA($request)['messages'];

    $response = response()->stream(function () use ($conversations, $request) {

            $stream = (new ChatService())->getstream(
                messages: $conversations,
                model: $request->model
            );

            foreach ($stream as $response) {

                $content = $response->choices[0]->delta->content ?? '';
                yield $content;
            }
        });

    ob_start();
    $response->sendContent();
    $output = ob_get_clean();

    // THEN
    expect($output)
        ->and($output)->not()->toContain('Exception')
        ->and($output)->not()->toContain('Error');
});

