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
        $conversations = Conversation::where("user_id", auth()->id())
            ->where('space_id', $request->conversationId)
            ->orderBy("created_at", "desc")
            ->get()
            ->reverse();

        logger()->info('ID: ' . $request->conversationId);

        $messages = [
            [
                'role' => 'system',
                'content' => 'Tu es un assistant utile et amical.'
            ]
        ];

        foreach ($conversations as $conv) {
            if (!empty($conv->question) && !empty($conv->response)) {
                $messages[] = [
                    'role' => 'user',
                    'content' => $conv->question
                ];
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $conv->response
                ];
            }
        }

        if (!empty($request->message)) {
            $messages[] = [
                'role' => 'user',
                'content' => $request->message
            ];
        }

        logger()->info('Messages envoyés à OpenAI : ' . json_encode($messages));

        return ['messages' => $messages];
    }

    public function getConversationByUserIdAndSpaceId($request)
    {
        return Conversation::where("user_id",auth()->user()->getAuthIdentifier())
            ->where('space_id', $request->space_id)
            ->get();
    }
}
