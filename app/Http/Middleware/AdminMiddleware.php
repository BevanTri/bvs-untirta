<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, \Closure $next): Response
    {
        if (!$request->user()?->is_admin) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(['message' => 'MAU NGAPAIN KAMU?'], 403);
            }
            abort(403);
        }
        return $next($request);
    }
}
