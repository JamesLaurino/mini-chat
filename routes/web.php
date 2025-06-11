<?php

use App\Http\Controllers\ChatController\AskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferenceController\PreferenceController;
use Inertia\Inertia;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {

    /********* STREAM ********/
    Route::post('/stream', [AskController::class, 'stream'])->name('broadcasting.stream');

    /*********** ASK ********/
    Route::controller(AskController::class)->group(function () {
        Route::get('/', 'index')->name('racine.index');
        Route::get('/ask', 'index')->name('ask.index');
        Route::get('/ask/{id}', 'show')->name('ask.show');
    });

    /********** SPACE *********/
    Route::post('/space', [AskController::class, 'beginNewSpace'])
        ->middleware('check.quota')
        ->name('space.create');

    /********** CONVERSATION *********/
    Route::post('/conversation', [AskController::class, 'addConversation'])
        ->middleware('check.quota')
        ->name('conversation.create');

    /********** PREFERENCES *********/
    Route::prefix('preference')->controller(PreferenceController::class)->group(function () {
        Route::get('/', 'index')->name('preference.index');
        Route::post('/about', 'storeAbout')->name('preference.about.store');
        Route::post('/instructions', 'storeInstruction')->name('preference.instruction.store');
        Route::post('/behaviour', 'storeBehaviour')->name('preference.behaviour.store');
    });
});

///********* STREAM ********/
//Route::post("/stream", [AskController::class,"stream"])
//    ->middleware('auth')
//    ->name("broadcasting.stream");
//
///*********** ASK ********/
//Route::get("/", [AskController::class,"index"])
//    ->middleware('auth')
//    ->name("racine.index");
//
//Route::get('/ask', [AskController::class, 'index'])
//    ->middleware("auth")
//    ->name('ask.index');
//
//Route::get('/ask/{id}', [AskController::class, 'show'])
//    ->middleware("auth")
//    ->name('ask.show');
//
///********** SPACE *********/
//Route::post('/space', [AskController::class,'beginNewSpace'])
//    ->middleware('auth',"check.quota")
//    ->name('space.create');
//
///********** CONVERSATION *********/
//Route::post('/conversation',[AskController::class,'addConversation'])
//    ->middleware("auth","check.quota")
//    ->name("conversation.create");
//
///********** PREFERENCES *********/
//Route::get("/preference", [PreferenceController::class, "index"])
//    ->middleware('auth')
//    ->name('preference.index');
//
//Route::post("/preference/about", [PreferenceController::class, "storeAbout"])
//    ->middleware('auth')
//    ->name('preference.about.store');
//
//Route::post("/preference/instructions", [PreferenceController::class, "storeInstruction"])
//    ->middleware('auth')
//    ->name('preference.instruction.store');
//
//Route::post("/preference/behaviour", [PreferenceController::class, "storeBehaviour"])
//    ->middleware('auth')
//    ->name('preference.behaviour.store');
