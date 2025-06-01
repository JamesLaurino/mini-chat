<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use App\Models\Space;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AskController extends Controller
{
    public function index()
    {
        $models = (new ChatService())->getModels();
        $selectedModel = ChatService::DEFAULT_MODEL;
        $userId = auth()->user()->getAuthIdentifier();
        $spaces = Space::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Ask/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
            'spaces' => $spaces
        ]);
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'model' => 'required|string',
        ]);

        try {
            $messages = [[
                'role' => 'user',
                'content' => $request->message,
            ]];

            $response = (new ChatService())->sendMessage(
                messages: $messages,
                model: $request->model
            );

            return redirect()->back()->with('message', $response);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
