<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCorsHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Expose-Headers', 'X-RateLimit-Limit, X-RateLimit-Remaining');
        
        return $response;
    }
}