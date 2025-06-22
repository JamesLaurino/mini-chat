<?php

namespace App\Services;

use App\Interfaces\ConversationRepositoryInterface;
use Exception;

class ConversationService
{

    protected $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function createConversationForNewSpace($request,$response,$space) {

        try {
            $conversation = $this->conversationRepository->createConversationForNewSpace($request,$response,$space);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la création d'une conversation pour un nouvel espace " . $e->getMessage());
        }

        return $conversation;
    }

    public function createFirstConversationForNewSpace($request,$space) {

        try {
            $conversation = $this->conversationRepository->createFirstConversationForNewSpace($request,$space);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la création d'une première conversation pour un nouvel espace " . $e->getMessage());
        }

        return $conversation;
    }

    public function addConversationInSpace($request) {

        try {
            $conversation = $this->conversationRepository->addConversationInSpace($request);
        } catch (Exception $e) {
            logger()->error("Erreur lors de l'ajout d'une conversation dans un espace : " . $e->getMessage());
        }

        return $conversation;
    }

    public function getConversationBySpaceId($spaceId) {

        try {
            $conversation = $this->conversationRepository->getConversationBySpaceId($spaceId);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupération d'une conversation par un espace id : " . $e->getMessage());
        }

        return $conversation;
    }

    public function getConversationForOpenIA($request) {

        try {
            $conversation = $this->conversationRepository->getConversationForOpenIA($request);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupération d'une conversation pour open IA : " . $e->getMessage());
        }

        return $conversation;
    }

    public function getConversationByUserIdAndSpaceId($request) {

        try {
            $conversation = $this->conversationRepository->getConversationByUserIdAndSpaceId($request);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupération d'une conversation par un user et un espace id : " . $e->getMessage());
        }

        return $conversation;
    }
}
