<?php

namespace App\Http\Controllers\SpaceController;

use App\Http\Controllers\Controller;
use App\Services\SpaceService;

class SpaceController extends Controller
{

    public function __construct(private SpaceService $spaceService){}


    public function destroy($id) {
        $this->spaceService->deleteSpaceById($id);
        return redirect()->back();
    }
}
