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

        if($user === null) {
            return $next($request);
        }

        if($user->role->value == "ADMIN")
        {
            return $next($request);
        }
        else {
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
}
