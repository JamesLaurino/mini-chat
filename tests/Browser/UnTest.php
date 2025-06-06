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
