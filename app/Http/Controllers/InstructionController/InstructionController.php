<?php

namespace App\Http\Controllers\InstructionController;

use App\Http\Controllers\Controller;
use App\Services\InstructionService;
use Inertia\Inertia;

class InstructionController extends Controller
{
    public function __construct(private InstructionService $instructionService){
    }

    public function index() {
        $preferences = $this->instructionService->getPreferencesByUserId();
        return Inertia::render('Instruction/Index',[
            'preferences' => $preferences
        ]);
    }

    public function store() {

    }
}
