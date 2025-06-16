<?php

namespace App\Interfaces;


interface PreferenceRepositoryInterface
{
    public function getPreferenceByUserIdSingle();

    public function getPreferencesByUserId();

    public function updatePreference($preference, $request, $column);

    public function createPreference($request,$column);
}
