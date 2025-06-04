<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Preference;

class PreferenceService
{
    public function getPreferenceByUserIdSingle() {
         return Preference::where("user_id",auth()->user()->getAuthIdentifier())
            ->first();
    }

    public function getPreferencesByUserId() {
        $userId = auth()->user()->getAuthIdentifier();
        return Preference::where("user_id", $userId)
            ->get();
    }

    public function updateAbout($preference, $request) {
        $preference->update([
            "about" => $request->message
        ]);
    }

    public function createPreference($request) {
        return Preference::create([
            'about' => $request->message
        ]);
    }
}
