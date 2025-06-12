<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('getQuota', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    // WHEN
    $service = app(\App\Services\MetricService::class);
    $res = $service->getQuota($user);

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res->request_count)->toBe(0);
    expect($res->date->toDateString())->toBe($res->date->toDateString());
    expect($res->user_id)->toBe($user->id);
});
