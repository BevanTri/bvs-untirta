<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class DetectAppUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $scheme = $request->header('X-Forwarded-Proto', $request->getScheme());
        $host = $request->header('X-Forwarded-Port', '')
            ? $request->header('X-Forwarded-Host', $request->getHttpHost())
            : $request->header('X-Forwarded-Host', $request->getHttpHost());

        URL::forceRootUrl("$scheme://$host");
        URL::forceScheme($scheme);

        return $next($request);
    }
}
