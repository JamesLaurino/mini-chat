<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Space;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AskController extends Controller
{
    public function index()
    {
        $conversations = [];
        $models = (new ChatService())->getModels();
        $selectedModel = ChatService::DEFAULT_MODEL;
        $userId = auth()->user()->getAuthIdentifier();
        $spaces = Space::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Ask/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
            'spaces' => $spaces,
            'conversations' => $conversations
        ]);
    }

    public function show($id)
    {
        $spaceId = $id;
        $models = (new ChatService())->getModels();
        $selectedModel = ChatService::DEFAULT_MODEL;
        $userId = auth()->user()->getAuthIdentifier();
        $spaces = Space::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        $conversations = Conversation::where("space_id",$spaceId)
            ->orderBy("created_at","asc")
            ->get();

        return Inertia::render('Ask/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
            'spaces' => $spaces,
            'conversations' => $conversations
        ]);
    }

    public function beginNewSpace(Request $request) {

        $request->validate([
            'message' => 'required|string',
            'model' => 'required|string',
        ]);

        try {
            //$messages = [[
            //    'role' => 'user',
           //     'content' => $request->message,
           // ]];
//            $response = (new ChatService())->sendMessage(
//                messages: $messages,
//                model: $request->model
//            );
            $response = (new ChatService())->generateLoremIpsum(1,50);

            $space = Space::create([
                'titre' => random_int(0, PHP_INT_MAX),
                'user_id' => auth()->user()->getAuthIdentifier()
            ]);

             Conversation::create([
                'question' => $request->message,
                'response' => $response,
                'user_id' => auth()->user()->getAuthIdentifier(),
                'space_id' => $space->id
            ]);

            return redirect()->route("ask.show", $space->id)->with('message', $response);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function addConversation(Request $request) {


        $request->validate([
            'question' => 'required|string',
            'response' => 'required|string',
            'space_id' => 'required'
        ]);

        $userId = auth()->user()->getAuthIdentifier();
        $conversations = Conversation::where("user_id",$userId)
            ->where('space_id', $request->space_id)
            ->get();

        if($conversations->isEmpty()) {
            return redirect()->back()->with('error', 'Erreur: ' . "Une erreur est survenue");
        }

        Conversation::create([
            'question' => $request->question,
            'response' => $request->response,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $request->space_id
        ]);

        return redirect()->back();
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'model' => 'required|string',
        ]);

        try {
//            $messages = [[
//                'role' => 'user',
//                'content' => $request->message,
//            ]];
//
//            $response = (new ChatService())->sendMessage(
//                messages: $messages,
//                model: $request->model
//            );
            $response = (new ChatService())->generateLoremIpsum(1,50);
            return redirect()->back()->with('message', $response);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
