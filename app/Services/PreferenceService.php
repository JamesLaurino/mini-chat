<?php

namespace App\Services;

use App\Interfaces\PreferenceRepositoryInterface;
use Exception;

class PreferenceService
{

    protected $preferenceRepository;

    public function __construct(PreferenceRepositoryInterface $preferenceRepository)
    {
        $this->preferenceRepository = $preferenceRepository;
    }


    public function getPreferenceByUserIdSingle() {
        try {
            $preference = $this->preferenceRepository->getPreferenceByUserIdSingle();
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupération de la préférence unique par l'id du user : " . $e->getMessage());
        }
         return $preference;
    }

    public function getPreferencesByUserId() {

        try {
            $preference = $this->preferenceRepository->getPreferencesByUserId();
        } catch (Exception $e) {
            logger()->error("Erreur lors de la récupération des préférences par l'id du user : " . $e->getMessage());
        }

        return $preference;
    }

    public function updatePreference($preference, $request, $column):void
    {
        try {
            $this->preferenceRepository->updatePreference($preference, $request, $column);
        } catch (Exception $e) {
            logger()->error("Erreur de la mise à jours des préférences : " . $e->getMessage());
        }
    }

    public function createPreference($request,$column) {

        try {
            $preference = $this->preferenceRepository->createPreference($request, $column);
        } catch (Exception $e) {
            logger()->error("Erreur de la création des préférences : " . $e->getMessage());
        }

        return $preference;
    }
}
