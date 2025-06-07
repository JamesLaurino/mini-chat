<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('Displays the mini-chat text on the home page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Forgot');
    });
});

it('allows authenticated user to see the dashboard', function () {
    $this->browse(function ($browser) {

        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->assertSee('Dashboard');
    });
});

it('allows authenticated user to see the side panel component', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        if (!$user) {
            $user = User::factory()->create(['id' => 2]);
        }

        $browser->loginAs($user)
            ->visit('/ask')
            ->screenshot('before-button-wait') // Add this!
            ->waitFor('button[aria-label="Ouvrir le panneau latéral"]') // Try this selector first!
            ->click('button[aria-label="Ouvrir le panneau latéral"]')
            ->waitFor('#side-panel-component')
            ->assertVisible('#side-panel-component')
            ->assertSeeIn('#side-panel-component', 'Conversations');
    });
});
