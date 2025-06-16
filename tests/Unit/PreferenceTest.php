<?php

use App\Http\Requests\Mocks\MockRequest;
use App\Models\Preference;

use App\Models\User;
use App\Services\PreferenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('test get preference by user id single', function () {

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
    $service = app(PreferenceService::class);
    $res = $service->getPreferenceByUserIdSingle();

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res["about"])->toBe("About 1");;
    expect($res["instruction"])->toBe("instruction 1");
    expect($res['user_id'])->toBe($user->id);;
});

it('test get preferences by user id', function () {

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
    $service = app(PreferenceService::class);
    $res = $service->getPreferencesByUserId();

    // THEN
    expect($res)->toHaveLength(1);
    expect($res[0]["about"])->toBe("About 1");;
    expect($res[0]["instruction"])->toBe("instruction 1");
    expect($res[0]['user_id'])->toBe($user->id);;
});

it('test update preference', function () {

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

    $request = new MockRequest();
    $request->message = "Update About";
    $column = "about";

    // WHEN
    $service = app(PreferenceService::class);
    $service->updatePreference($preference,$request,$column);

    $res = Preference::where('user_id',$user->id)->get();

    // THEN
    expect($res)->toHaveLength(1);
    expect($res[0]["about"])->toBe("Update About");;
    expect($res[0]["instruction"])->toBe("instruction 1");
    expect($res[0]['user_id'])->toBe($user->id);;
});

it('test create preference', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);
    $this->actingAs($user);


    $request = new MockRequest();
    $request->message = "About";
    $column = "about";

    // WHEN
    $service = app(PreferenceService::class);
    $res = $service->createPreference($request,$column);

    // THEN
    expect($res)->not()->toBeEmpty();
    expect($res["about"])->toBe("About");
    expect($res["instruction"])->toBe(null);
});
