<?php

namespace App\Services;

use App\Interfaces\ConversationRepositoryInterface;

class ConversationService
{

    protected $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function createConversationForNewSpace($request,$response,$space) {
        return $this->conversationRepository->createConversationForNewSpace($request,$response,$space);
    }

    public function createFirstConversationForNewSpace($request,$space) {
        return $this->conversationRepository->createFirstConversationForNewSpace($request,$space);
    }

    public function addConversationInSpace($request) {
        return $this->conversationRepository->addConversationInSpace($request);
    }

    public function getConversationBySpaceId($spaceId) {
        return $this->conversationRepository->getConversationBySpaceId($spaceId);
    }

    public function getConversationForOpenIA($request) {
        return $this->conversationRepository->getConversationForOpenIA($request);
    }

    public function getConversationByUserIdAndSpaceId($request) {
        return $this->conversationRepository->getConversationByUserIdAndSpaceId($request);
    }
}
