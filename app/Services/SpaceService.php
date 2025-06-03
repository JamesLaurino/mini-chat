<?php

namespace App\Services;

use App\Models\Space;

class SpaceService
{
    public function createNewSpace() {
        // TODO make another call for the title
        return Space::create([
            'titre' => random_int(0, PHP_INT_MAX),
            'user_id' => auth()->user()->getAuthIdentifier()
        ]);
    }

    public function getSpaceByUserId() {
        return Space::where('user_id', auth()->user()->getAuthIdentifier())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
