<?php

namespace App\Repositories;

use App\Interfaces\MetricRepositoryInterface;
use App\Models\Metric;
use Illuminate\Support\Carbon;

class MetricRepository implements MetricRepositoryInterface
{

    public function getQuota($user)
    {
        $today = Carbon::today();
        return Metric::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['request_count' => 0]
        );
    }
}
