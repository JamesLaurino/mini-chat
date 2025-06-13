<?php

namespace App\Services;

use App\Models\Space;

class SpaceService
{
    public function createNewSpace($titre) {
        return Space::create([
            'titre' => $titre,
            'user_id' => auth()->user()->getAuthIdentifier()
        ]);
    }

    public function getSpaceByUserId() {
        return Space::where('user_id', auth()->user()->getAuthIdentifier())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteSpaceById($spaceId) {
        $post = Space::find($spaceId);
        if ($post) {
            $post->delete();
        }
    }
}
