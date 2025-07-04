<?php

namespace App\Interfaces;


interface ConversationRepositoryInterface
{
    public function createConversationForNewSpace($request,$response,$space);

    public function createFirstConversationForNewSpace($request,$space);

    public function addConversationInSpace($request);

    public function getConversationBySpaceId($spaceId);

    public function getConversationForOpenIA($request);

    public function getConversationByUserIdAndSpaceId($request);
}
