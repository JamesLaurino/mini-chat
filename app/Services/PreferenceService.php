<?php

namespace App\Services;

use App\Interfaces\PreferenceRepositoryInterface;

class PreferenceService
{

    protected $preferenceRepository;

    public function __construct(PreferenceRepositoryInterface $preferenceRepository)
    {
        $this->preferenceRepository = $preferenceRepository;
    }


    public function getPreferenceByUserIdSingle() {
         return $this->preferenceRepository->getPreferenceByUserIdSingle();
    }

    public function getPreferencesByUserId() {
        return $this->preferenceRepository->getPreferencesByUserId();
    }

    public function updatePreference($preference, $request, $column):void
    {
        $this->preferenceRepository->updatePreference($preference, $request, $column);
    }

    public function createPreference($request,$column) {
        return $this->preferenceRepository->createPreference($request, $column);
    }
}
