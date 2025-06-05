<?php

use App\Http\Controllers\ChatController\AskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferenceController\PreferenceController;
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

/********* STREAM ********/
Route::post("/stream", [AskController::class,"stream"])
    ->middleware('auth')
    ->name("broadcasting.stream");

/*********** ASK ********/
Route::get('/ask', [AskController::class, 'index'])
    ->middleware("auth")
    ->name('ask.index');

Route::post('/ask', [AskController::class, 'ask'])
    ->middleware("auth")
    ->name('ask.post');

Route::get('/ask/{id}', [AskController::class, 'show'])
    ->middleware("auth")
    ->name('ask.show');

/********** SPACE *********/
Route::post('/space', [AskController::class,'beginNewSpace'])
    ->middleware('auth',"check.quota")
    ->name('space.create');

/********** CONVERSATION *********/
Route::post('/conversation',[AskController::class,'addConversation'])
    ->middleware("auth","check.quota")
    ->name("conversation.create");

/********** PREFERENCES *********/
Route::get("/preference", [PreferenceController::class, "index"])
    ->middleware('auth')
    ->name('preference.index');

Route::post("/preference/about", [PreferenceController::class, "storeAbout"])
    ->middleware('auth')
    ->name('preference.about.store');

Route::post("/preference/instructions", [PreferenceController::class, "storeInstruction"])
    ->middleware('auth')
    ->name('preference.instruction.store');

Route::post("/preference/behaviour", [PreferenceController::class, "storeBehaviour"])
    ->middleware('auth')
    ->name('preference.behaviour.store');
