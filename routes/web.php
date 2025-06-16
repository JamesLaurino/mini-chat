<?php

use App\Http\Controllers\ChatController\AskController;
use App\Http\Controllers\SpaceController\SpaceController;
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

    Route::controller(AskController::class)->group(function () {
        Route::get('/', 'index')->name('racine.index');
        Route::get('/ask', 'index')->name('ask.index');
        Route::get('/ask/{id}', 'show')->name('ask.show');
        Route::post('/stream', 'stream')->name('broadcasting.stream');

        Route::middleware('check.quota')->group(function () {
            Route::post('/space', 'beginNewSpace')->name('space.create');
            Route::post('/conversation', 'addConversation')->name('conversation.create');
        });
    });

    Route::delete('/space/delete/{id}', [SpaceController::class, 'destroy'])
        ->name('space.destroy');

    Route::prefix('preference')->controller(PreferenceController::class)->group(function () {
        Route::get('/', 'index')->name('preference.index');
        Route::post('/about', 'storeAbout')->name('preference.about.store');
        Route::post('/instructions', 'storeInstruction')->name('preference.instruction.store');
        Route::post('/behaviour', 'storeBehaviour')->name('preference.behaviour.store');
    });
});
