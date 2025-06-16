<?php

namespace App\Repositories;

use App\Interfaces\SpaceRepositoryInterface;
use App\Models\Space;

class SpaceRepository implements SpaceRepositoryInterface
{
    public function createNewSpace($titre)
    {
        return Space::create([
            'titre' => $titre,
            'user_id' => auth()->user()->getAuthIdentifier()
        ]);
    }

    public function getSpaceByUserId()
    {
        return Space::where('user_id', auth()->user()->getAuthIdentifier())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteSpaceById($spaceId)
    {
        $post = Space::find($spaceId);
        if ($post) {
            $post->delete();
        } else {
            throw new \Exception("Vous n'êtes pas autorisé à supprimer cet espace.");
        }
    }
}
