<?php

namespace App\Http\Controllers\InstructionController;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class InstructionController extends Controller
{
    public function index() {
        return Inertia::render('Instruction/Index');
    }
}
