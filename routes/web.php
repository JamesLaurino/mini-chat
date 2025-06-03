<?php

use App\Http\Controllers\ChatController\AskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructionController\InstructionController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/ask', [AskController::class, 'index'])
    ->middleware("auth")
    ->name('ask.index');
Route::post('/ask', [AskController::class, 'ask'])
    ->middleware("auth")
    ->name('ask.post');

Route::get('/ask/{id}', [AskController::class, 'show'])
    ->middleware("auth")
    ->name('ask.show');

Route::post('/space', [AskController::class,'beginNewSpace'])
    ->middleware('auth')
    ->name('space.create');

Route::post('/conversation',[AskController::class,'addConversation'])
    ->middleware("auth")
    ->name("conversation.create");

Route::get("/instructions", [InstructionController::class, "index"])
    ->middleware('auth')
    ->name('instruction.index');
