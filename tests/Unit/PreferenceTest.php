<?php

use App\Models\Preference;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('getPreferenceByUserIdSingle', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

    Preference::factory()->create([
        'about' => 'About 1',
        'instruction' => 'instruction 1',
        'behaviour' => 'behaviour 1',
        'user_id' => $user->id
    ]);


    // WHEN
    $service = app(\App\Services\PreferenceService::class);
    $res = $service->getPreferenceByUserIdSingle();

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res["about"])->toBe("About 1");;
    expect($res["instruction"])->toBe("instruction 1");
    expect($res['user_id'])->toBe($user->id);;
});

it('getPreferencesByUserId', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

    Preference::factory()->create([
        'about' => 'About 1',
        'instruction' => 'instruction 1',
        'behaviour' => 'behaviour 1',
        'user_id' => $user->id
    ]);


    // WHEN
    $service = app(\App\Services\PreferenceService::class);
    $res = $service->getPreferencesByUserId();

    // THEN
    expect($res)->toHaveLength(1);
    expect($res[0]["about"])->toBe("About 1");;
    expect($res[0]["instruction"])->toBe("instruction 1");
    expect($res[0]['user_id'])->toBe($user->id);;
});

it('updatePreference', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);

    $preference = Preference::factory()->create([
        'about' => 'About 1',
        'instruction' => 'instruction 1',
        'behaviour' => 'behaviour 1',
        'user_id' => $user->id
    ]);

    $request = new \App\Http\Requests\Mocks\MockRequest();
    $request->message = "Update About";
    $column = "about";

    // WHEN
    $service = app(\App\Services\PreferenceService::class);
    $service->updatePreference($preference,$request,$column);

    $res = Preference::where('user_id',$user->id)->get();

    // THEN
    expect($res)->toHaveLength(1);
    expect($res[0]["about"])->toBe("Update About");;
    expect($res[0]["instruction"])->toBe("instruction 1");
    expect($res[0]['user_id'])->toBe($user->id);;
});

it('createPreference', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);


    $request = new \App\Http\Requests\Mocks\MockRequest();
    $request->message = "About";
    $column = "about";

    // WHEN
    $service = app(\App\Services\PreferenceService::class);
    $res = $service->createPreference($request,$column);

    // THEN
    expect($res)->not()->toBeEmpty();
    expect($res["about"])->toBe("About");
    expect($res["instruction"])->toBe(null);
});
