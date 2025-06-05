<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use App\Http\Requests\AskRequest;
use App\Http\Requests\ConversationRequest;
use App\Services\ChatService;
use App\Services\ConversationService;
use App\Services\SpaceService;
use App\Services\WebService;
use Illuminate\Http\Client\Request;
use Inertia\Inertia;

class AskController extends Controller
{

    public function __construct(
        private WebService $webService,
        private SpaceService $spaceService,
        private ConversationService $conversationService){
    }

    public function index()
    {
        $conversations = [];
        $models = (new ChatService())->getModels();
        $selectedModel = ChatService::DEFAULT_MODEL;
        $spaces = $this->spaceService->getSpaceByUserId();

        return Inertia::render('Ask/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
            'spaces' => $spaces,
            'conversations' => $conversations
        ]);
    }

    /**
     * @throws \Exception
     */
    public function stream(Request $request)
    {
        // return new new ChatService())->getStreamMock();
        $messages = [[
            'role' => 'user',
            'content' => $request->message,
        ]];

        $stream = (new ChatService())->getStream(
            messages: $messages,
            model: $request->model
        );

        return response()->stream(function () use ($stream) {
            foreach ($stream as $response) {
                yield $response;
                //yield $response->choices[0]->delta->content ?? '';
            }
        });
    }

    public function show($id)
    {
        $models = (new ChatService())->getModels();
        $selectedModel = ChatService::DEFAULT_MODEL;
        $spaces = $this->spaceService->getSpaceByUserId();
        $conversations = $this->conversationService->getConversationBySpaceId($id);

        return Inertia::render('Ask/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
            'spaces' => $spaces,
            'conversations' => $conversations
        ]);
    }

    public function beginNewSpace(AskRequest $request) {

        try {

            //$response = $this->webService->getResponse($request);
            $response = (new ChatService())->generateLoremIpsum(1,50);

            //$titre = $this->webService->getResponse($request, "Génère un titre pour ce message (maximum 4 mots) :");
            $titre = (new ChatService())->generateLoremIpsum(1,2);

            $space = $this->spaceService->createNewSpace($titre);
            $this->conversationService->createConversationForNewSpace($request,$response,$space);

            return redirect()->route("ask.show", $space->id)->with('message', $response);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function addConversation(ConversationRequest $request) {

        $conversations = $this->conversationService->getConversationByUserIdAndSpaceId($request);

        if($conversations->isEmpty()) {
            return redirect()->back()->with('error', 'Erreur: ' . "Une erreur est survenue");
        }

        $this->conversationService->addConversationInSpace($request);

        return redirect()->back()->with('error', 'Erreur: ajout de la conversation impossible');
    }

    public function ask(AskRequest $request)
    {
        try {
            //$response = $this->webService->getResponse($request);
            $response = (new ChatService())->generateLoremIpsum(1,50);
            return redirect()->back()->with('message', $response);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
