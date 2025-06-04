<?php

namespace App\Http\Middleware;

use App\Models\Metric;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class CheckDailyQuota
{

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $today = Carbon::today();

        $quota = Metric::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['request_count' => 0]
        );


        if ($quota->request_count >= 50) {

            return Inertia::render('Error/Index');
        }

        $quota->increment('request_count');

        return $next($request);
    }
}
