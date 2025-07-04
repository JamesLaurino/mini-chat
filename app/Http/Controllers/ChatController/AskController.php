<?php

namespace App\Http\Controllers\ChatController;


use App\Http\Controllers\Controller;
use App\Http\Requests\AskRequest;
use App\Http\Requests\ConversationRequest;
use App\Http\Requests\StreamRequest;
use App\Services\ChatService;
use App\Services\ConversationService;
use App\Services\SpaceService;
use App\Services\WebService;
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
    public function stream(StreamRequest $request)
    {
        $conversation = $this->conversationService->getConversationForOpenIA($request)['messages'];
        return response()->stream(function () use ($conversation, $request) {

            $stream = (new ChatService())->getstream(
                messages: $conversation,
                model: $request->model
            );

            foreach ($stream as $response) {

                $content = $response->choices[0]->delta->content ?? '';
                yield $content;
                usleep(100000);
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
            'conversations' => $conversations,
            'space' => $id
        ]);
    }

    public function beginNewSpace(AskRequest $request) {
        try {

            $titre = $this->webService->getResponse($request, "Génère un titre de maximum 4 mots pour ce message :");

            $space = $this->spaceService->createNewSpace($titre);

            $this->conversationService->createFirstConversationForNewSpace($request,$space);

            return redirect()->route("ask.show", $space->id)->with('message', 'redirection réussie');
        } catch (\Exception $e) {
            logger()->error("Une erreur est survenue lors de la création d'un nouvel espace : " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function addConversation(ConversationRequest $request) {

        $conversations = $this->conversationService->getConversationByUserIdAndSpaceId($request);

        if($conversations->isEmpty()) {
            logger()->error("Une erreur est survenue lors de l'ajout d'une conversation : la liste des conversations est vide");
            return redirect()->back()->with('error', 'Erreur: Une erreur technique est survenue');
        }

        $this->conversationService->addConversationInSpace($request);

        return redirect()->back()->with('message', 'Nouvelle conversation enregistrée avec success');
    }

}
