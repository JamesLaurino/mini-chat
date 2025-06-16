<?php

namespace App\Services;

use App\Interfaces\SpaceRepositoryInterface;
use Exception;

class SpaceService
{

    protected $spaceRepository;

    public function __construct(SpaceRepositoryInterface $spaceRepository)
    {
        $this->spaceRepository = $spaceRepository;
    }

    public function createNewSpace($titre) {
        return $this->spaceRepository->createNewSpace($titre);
    }

    public function getSpaceByUserId() {
        return $this->spaceRepository->getSpaceByUserId();
    }

    public function deleteSpaceById($spaceId) {
        try {
            $this->spaceRepository->deleteSpaceById($spaceId);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la suppression de l'espace $spaceId: " . $e->getMessage());
        }
    }
}
