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

        try {
            $preference = $this->preferenceService->getPreferenceByUserIdSingle();

            if($preference != null) {
                $this->preferenceService->updatePreference($preference,$request, "instruction");
            } else {
                $this->preferenceService->createPreference($request, "instruction");
            }
            return redirect()->route('preference.index')->with('message', 'Commandes mise à jours');

        } catch (\Exception $e) {
            logger()->error("Une erreur est survenue lors de la mise à jours des commandes : " . $e->getMessage());
            return redirect()->route('preference.index')->with('error', 'Une erreur est survenue pendant la mise à jours');
        }
    }

    public function storeBehaviour(PreferenceRequest $request) {

        try {
            $preference = $this->preferenceService->getPreferenceByUserIdSingle();

            if($preference != null) {
                $this->preferenceService->updatePreference($preference,$request,"behaviour");
            } else {
                $this->preferenceService->createPreference($request,"behaviour");
            }
            return redirect()->route('preference.index')->with('message', 'Comportement mis à jours');
        }
        catch (\Exception $e) {
            logger()->error("Une erreur est survenue lors de la mise à jours du comportement : " . $e->getMessage());
            return redirect()->route('preference.index')->with('error', 'Une erreur est survenue pendant la mise à jours');
        }
    }

    public function storeAbout(PreferenceRequest $request) {

        try {
            $preference = $this->preferenceService->getPreferenceByUserIdSingle();

            if($preference != null) {
                $this->preferenceService->updatePreference($preference,$request,"about");
            } else {
                $this->preferenceService->createPreference($request,"about");
            }
            return redirect()->route('preference.index')->with('message', 'A propos mis à jours');
        }
        catch (\Exception $e) {
            logger()->error("Une erreur est survenue lors de la mise à jours du about :" . $e->getMessage());
            return redirect()->route('preference.index')->with('error', 'Une erreur est survenue pendant la mise à jours');
        }

    }
}
