<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetRobotsHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('front-assets/*') || $request->is('assets/*') || $request->is('uploads/*')) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return $response;
        }

        if ($response->headers->has('X-Robots-Tag')) {
            return $response;
        }

        $robots = ($request->is('yonetim*') || $response->getStatusCode() >= 400)
            ? 'noindex, nofollow'
            : 'index, follow';

        $response->headers->set('X-Robots-Tag', $robots);

        return $response;
    }
}
