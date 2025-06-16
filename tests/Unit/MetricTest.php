<?php

use App\Models\User;
use App\Services\MetricService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);


it('test get quota', function () {

    // GIVEN
    $user = User::factory()->create([
        'name' => 'Alice',
    ]);

    // WHEN
    $service = app(MetricService::class);
    $res = $service->getQuota($user);

    // THEN
    expect($res)->not->toBeEmpty()->exists()->toBeTrue();
    expect($res->request_count)->toBe(0);
    expect($res->date->toDateString())->toBe($res->date->toDateString());
    expect($res->user_id)->toBe($user->id);
});
