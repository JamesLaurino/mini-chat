<?php

namespace App\Services;

use App\Models\Metric;
use Illuminate\Support\Carbon;

class MetricService
{
    public function getQuota($user) {

        $today = Carbon::today();
        return Metric::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['request_count' => 0]
        );
    }
}
