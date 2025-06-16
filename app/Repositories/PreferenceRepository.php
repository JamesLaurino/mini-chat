<?php

namespace App\Repositories;

use App\Interfaces\PreferenceRepositoryInterface;
use App\Models\Preference;

class PreferenceRepository implements PreferenceRepositoryInterface
{

    public function getPreferenceByUserIdSingle()
    {
        return Preference::where("user_id",auth()->user()->getAuthIdentifier())
            ->first();
    }

    public function getPreferencesByUserId()
    {
        $userId = auth()->user()->getAuthIdentifier();
        return Preference::where("user_id", $userId)
            ->get();
    }

    public function updatePreference($preference, $request, $column)
    {
        $preference->update([
            $column => $request->message
        ]);
    }

    public function createPreference($request, $column)
    {
        return Preference::create([
            $column => $request->message
        ]);
    }
}
