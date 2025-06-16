<?php

namespace App\Interfaces;


interface SpaceRepositoryInterface
{
    public function createNewSpace($titre);

    public function getSpaceByUserId();

    public function deleteSpaceById($spaceId);
}
