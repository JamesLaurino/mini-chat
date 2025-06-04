<?php

namespace App\Http\Controllers\PreferenceController;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Services\PreferenceService;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    public function __construct(private PreferenceService $preferenceService){
    }

    public function index() {
        $preferences = $this->preferenceService->getPreferencesByUserId();
        return Inertia::render('Instruction/Index',[
            'preferences' => $preferences
        ]);
    }

    public function storeAbout(PreferenceRequest $request) {
        $preference = $this->preferenceService->getPreferenceByUserIdSingle();

        if($preference != null) {
            $this->preferenceService->updateAbout($preference,$request);
        } else {
            $this->preferenceService->createPreference($request);
        }

        return redirect()->route('preference.index');
    }
}
