<?php

use App\Http\Requests\Mocks\MockRequest;
use App\Models\Conversation;
use App\Models\Space;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenAI\Responses\StreamResponse;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


//it('getResponse', function () {
//
//    $user = User::factory()->create([
//        'name' => 'Alice',
//    ]);
//
//    $request = new MockRequest();
//    $request->model = "openai/gpt-4.1-mini";
//    $request->message = "Bienvenue à toi cher amis dans ce jeu incroyable";
//    $this->actingAs($user);
//
//
//    // WHEN
//    $service = app(\App\Services\WebService::class);
//    $res = $service->getResponse($request, "Génère un titre de maximum 4 mots pour ce message :");
//
//    // THEN
//    expect($res)->not()->toBeEmpty();
//});

it('getStream', function () {

    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    $space = Space::factory()->create([
        'titre' => 'Space 1',
        'user_id' => $user->id
    ]);

    $this->actingAs($user);

    $conversation = Conversation::create([
        'question' => "coucou 1",
        'response' => "",
        'user_id' => $user->id,
        'space_id' => $space->id
    ]);

    $request = new MockRequest();
    $request->conversationId = $conversation->id;
    $request->message = "Coucou";
    $request->model = "openai/gpt-4.1-mini";

    //$serviceConversation = app(\App\Services\ConversationService::class);
    //$conversations = $serviceConversation->getConversationForOpenIA($request)['messages'];

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


//    $res = response()->stream(function () use ($conversations, $request) {
//
//            $stream = (new ChatService())->getstream(
//                messages: $conversations,
//                model: $request->model
//            );
//
//            foreach ($stream as $response) {
//
//                $content = $response->choices[0]->delta->content ?? '';
//                yield $content;
//                usleep(100000);
//            }
//        });

});
