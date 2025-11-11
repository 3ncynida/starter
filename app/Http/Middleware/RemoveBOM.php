<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveBOM
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('content-type') === 'application/json' || 
            str_contains($response->headers->get('content-type') ?? '', 'application/json')) {
            
            $content = $response->getContent();
            // Remove BOM if present
            if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
                $content = substr($content, 3);
                $response->setContent($content);
            }
        }

        return $response;
    }
}
