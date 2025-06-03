<?php

namespace App\Services;

use App\Models\Conversation;

class ConversationService
{
    public function createConversationForNewSpace($request,$response,$space) {
        return Conversation::create([
            'question' => $request->message,
            'response' => $response,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $space->id
        ]);
    }

    public function addConversationInSpace($request) {
        return Conversation::create([
            'question' => $request->question,
            'response' => $request->response,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'space_id' => $request->space_id
        ]);
    }

    public function getConversationBySpaceId($spaceId) {
        return Conversation::where("space_id",$spaceId)
            ->orderBy("created_at","asc")
            ->get();
    }

    public function getConversationByUserIdAndSpaceId($request) {
        return Conversation::where("user_id",auth()->user()->getAuthIdentifier())
            ->where('space_id', $request->space_id)
            ->get();
    }
}
