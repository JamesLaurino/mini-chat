<?php

namespace App\Http\Middleware;

use App\Models\Metric;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckDailyQuota
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $today = Carbon::today();

        $quota = Metric::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['request_count' => 0]
        );


        if ($quota->request_count >= 50) {
            return response()->json(['message' => 'Daily request quota exceeded'], 429);
        }

        // Optionnel : incrémenter ici ou après une requête réussie
        $quota->increment('request_count');


        return $next($request);
    }
}
