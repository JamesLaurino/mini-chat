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
        try {
            $space = $this->spaceRepository->createNewSpace($titre);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la création de l'espace ayant pour titre $titre : " . $e->getMessage());
        }
        return $space;
    }

    public function getSpaceByUserId() {
        try {
             $space = $this->spaceRepository->getSpaceByUserId();
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupèration de l'espace par son id : " . $e->getMessage());
        }

        return $space;
    }

    public function deleteSpaceById($spaceId) {
        try {
            $this->spaceRepository->deleteSpaceById($spaceId);
        } catch (Exception $e) {
            logger()->error("Erreur lors de la suppression de l'espace avec l'id $spaceId : " . $e->getMessage());
        }
    }
}
