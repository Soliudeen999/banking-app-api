<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasTrnxPin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(!$user?->trnx_pin) {
            return response()->json([
                'message' => 'Transaction PIN is not set. Please set your transaction PIN to proceed.',
            ], 403);
        }

        return $next($request);
    }
}
