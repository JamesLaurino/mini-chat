<?php

namespace App\Repositories;

use App\Interfaces\ConversationRepositoryInterface;
use App\Models\Conversation;

class ConversationRepository implements ConversationRepositoryInterface
{

    public function createConversationForNewSpace($request, $response, $space)
    {
        return Conversation::create([
            'question' => $request->message,
            'response' => $response,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $space->id
        ]);
    }

    public function createFirstConversationForNewSpace($request, $space)
    {
        return Conversation::create([
            'question' => $request->message,
            'response' => "",
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $space->id
        ]);
    }

    public function addConversationInSpace($request)
    {
        return Conversation::create([
            'question' => $request->question,
            'response' => $request->response,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $request->space_id
        ]);
    }

    public function getConversationBySpaceId($spaceId)
    {
        return Conversation::where("space_id",$spaceId)
            ->where("response", "!=", "")
            ->orderBy("created_at","asc")
            ->get();
    }

    public function getConversationForOpenIA($request)
    {
        $conversations = Conversation::where("user_id",auth()->user()->getAuthIdentifier())
            ->where('space_id', $request->conversationId)
            ->orderBy("created_at","asc")
            ->get();

        $messages = [];

        foreach ($conversations as $conv) {
            $messages[] = [
                'role' => 'user',
                'content' => $conv->question
            ];

            $messages[] = [
                'role' => 'assistant',
                'content' => $conv->response
            ];
        }

        // Ajout du message actuel
        if ($request->message) {
            $messages[] = [
                'role' => 'user',
                'content' => $request->message
            ];
        }


        return ['messages' => $messages];
    }

    public function getConversationByUserIdAndSpaceId($request)
    {
        return Conversation::where("user_id",auth()->user()->getAuthIdentifier())
            ->where('space_id', $request->space_id)
            ->get();
    }
}
