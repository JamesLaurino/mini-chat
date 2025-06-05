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

    public function storeInstruction(PreferenceRequest $request) {
        $preference = $this->preferenceService->getPreferenceByUserIdSingle();

        if($preference != null) {
            $this->preferenceService->updatePreference($preference,$request, "instruction");
        } else {
            $this->preferenceService->createPreference($request, "instruction");
        }

        return redirect()->route('preference.index');
    }

    public function storeBehaviour(PreferenceRequest $request) {
        $preference = $this->preferenceService->getPreferenceByUserIdSingle();

        if($preference != null) {
            $this->preferenceService->updatePreference($preference,$request,"behaviour");
        } else {
            $this->preferenceService->createPreference($request,"behaviour");
        }

        return redirect()->route('preference.index');
    }

    public function storeAbout(PreferenceRequest $request) {
        $preference = $this->preferenceService->getPreferenceByUserIdSingle();

        if($preference != null) {
            $this->preferenceService->updatePreference($preference,$request,"about");
        } else {
            $this->preferenceService->createPreference($request,"about");
        }

        return redirect()->route('preference.index');
    }
}
