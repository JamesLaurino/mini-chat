<?php

namespace App\Http\Middleware;

use App\Models\Metric;
use App\Services\MetricService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class CheckDailyQuota
{
    public function __construct(private MetricService $metricService){}


    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if($user === null) {
            return $next($request);
        }

        if($user->role->value == "ADMIN")
        {
            return $next($request);
        }
        else {

            $quota = $this->metricService->getQuota($user);

            if ($quota->request_count >= 50) {
                return Inertia::render('Error/Index');
            }

            $quota->increment('request_count');
            return $next($request);
        }
    }
}
