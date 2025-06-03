<?php

namespace App\Services;

use App\Models\Preference;

class InstructionService
{
    public function getPreferencesByUserId() {
        $userId = auth()->user()->getAuthIdentifier();
        return Preference::where("user_id", $userId)
            ->get();
    }
}
