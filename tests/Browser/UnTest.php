<?php

use App\Models\User;
use Laravel\Dusk\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;

/***************************** NO AUTH TESTS ************************/
/********************************************************************/

it('test user cannot access chat without login', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/ask')
            ->assertRouteIs('login');
    });
});


test('Displays the mini-chat text on the home page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Forgot');
    });
});

/***************************** CHECK COMPONENTS ************************/
/**********************************************************************/

it('allows authenticated user to see the dashboard', function () {
    $this->browse(function ($browser) {

        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->waitForText('Dashboard', 5) // attend jusqu’à 5 secondes
            ->assertSee('Dashboard');
    });
});

it('allows authenticated user to see the side panel component', function () {
    $user = User::find(2);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/ask')
            ->waitFor('button[aria-label="Ouvrir le panneau latéral"]')
            ->click('button[aria-label="Ouvrir le panneau latéral"]')
            ->waitFor('#side-panel-component')
            ->assertVisible('#side-panel-component');

    });
});


/***************************** CONVERSATION TESTS ************************/
/*************************************************************************/

it('test user can send message', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);

        $browser->loginAs($user)
            ->visit('/ask')
            ->pause(5000)
            ->type('#message', 'Test message')
            ->click('button[type="submit"]')
            ->waitFor('.loading.loading-spinner')
            ->assertPresent('.loading.loading-spinner')
            ->pause(2000)
            ->assertSee('Test message');
    });
});


it('test user cannot send message', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->assertAttributeContains('button[type="submit"]', 'disabled', 'true');
    });
});


it('test model selected work', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->click('button[aria-label="Afficher/Masquer les options"]')
            ->waitFor('#model')
            ->select('#model', 'openai/gpt-4.1-mini')
            ->assertSelected('#model', 'openai/gpt-4.1-mini');
    });
});

/****************************** SPACES TESTS *****************************/
/*************************************************************************/

it('test create new spaces', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->waitFor('#message')
            ->type('#message', 'Premier message dans un nouveau space')
            ->click('button[type="submit"]')
            ->waitFor('.loading.loading-spinner')
            ->assertPathBeginsWith('/ask/');
    });
});

it('test switch between spaces', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
        ->visit('/ask')
        ->click('.btn.btn-square.fixed.top-4.left-4')
        ->waitFor('#side-panel-component')
        ->assertVisible('#side-panel-component')
        ->clickLink('Nouvelle conversation') // Assurez-vous d'ajouter ce texte à votre bouton
        ->pause(2000)
        ->assertPathIs('/ask');
    });
});

/****************************** STREAMING ********************************/
/*************************************************************************/

it('test sSTREAMING RESPONSE APPEAR', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->waitFor('#message')
            ->type('#message', 'Test streaming')
            ->click('button[type="submit"]')
            ->waitFor('.loading.loading-spinner')
            ->assertSee('Test streaming'); // Le message envoyé
    });
});

it('test during streaming input is disable', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->waitFor('#message')
            ->type('#message', 'Test streaming')
            ->click('button[type="submit"]')
            ->waitFor('.loading.loading-spinner')
            ->assertAttributeContains('button[type="submit"]', 'disabled', 'true');
    });
});


/****************************** INTERFACE ********************************/
/************************************************************************/

it('test text area expense', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/ask')
            ->waitFor('#message')
            ->type('#message', str_repeat("Test\n", 30))
            ->script('return document.getElementById("message").offsetHeight;');

        $height = $browser->script('return document.getElementById("message").offsetHeight;')[0];
        $this->assertTrue($height > 100, "Le textarea ne s'est pas expand comme attendu. Hauteur : $height");
    });
});

it('test preference page', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->assertPresent('input[aria-label="Commandes"]')
            ->assertPresent('input[aria-label="Comportements"]');
    });
});

it('test preference orders click', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->click('input[aria-label="Commandes"]')
            ->assertSee('Commandes');
    });
});

it('test preference behaviour click', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->click('input[aria-label="Comportements"]')
            ->assertSee('Instructions');
    });
});

/****************************** PREFERENCES ********************************/
/************************************************************************/

it('test update about preference', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->waitFor('.tabs')
            ->type('#about-textarea', 'Texte modifié about in test')
            ->click('@submit-about')
            // Vérifier que le nouveau texte est bien enregistré
            ->assertInputValue('#about-textarea', 'Texte modifié about in test');
    });

});

it('test update commande preference', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->waitFor('.tabs')
            ->click('input[aria-label="Commandes"]')
            ->type('#commande-textarea', 'Texte modifié commande in test')
            ->click('@submit-commande')
            ->assertInputValue('#commande-textarea', 'Texte modifié commande in test');
    });
});

it('test update behaviour preference', function () {
    $this->browse(function (Browser $browser) {
        $user = User::find(2);
        $browser->loginAs($user)
            ->visit('/preference')
            ->waitFor('.tabs')
            ->click('input[aria-label="Comportements"]')
            ->type('#comportement-textarea', 'Texte modifié comportement in test')
            ->click('@submit-comportement')
            ->assertInputValue('#comportement-textarea', 'Texte modifié comportement in test');
    });
});
