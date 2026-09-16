<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureImageAlts
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('yonetim*') || $request->is('front-assets/*') || $request->is('assets/*')) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $html = $response->getContent();
        if (! is_string($html) || $html === '' || ! str_contains($html, '<img')) {
            return $response;
        }

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function (array $matches) {
            $attrs = $matches[1];

            if (preg_match('/\balt\s*=\s*("([^"]*)"|\'([^\']*)\'|[^\s>]+)/i', $attrs, $altMatch)) {
                $altValue = $altMatch[2] ?? $altMatch[3] ?? trim($altMatch[1], "\"'");
                if (trim((string) $altValue) !== '') {
                    return $matches[0];
                }

                $attrs = preg_replace('/\s*alt\s*=\s*("([^"]*)"|\'([^\']*)\'|[^\s>]+)/i', '', $attrs);
            }

            $src = '';
            if (preg_match('/\bsrc\s*=\s*("([^"]*)"|\'([^\']*)\')/i', $attrs, $srcMatch)) {
                $src = $srcMatch[2] ?? $srcMatch[3] ?? '';
            }

            $alt = e(image_alt_from_src($src));

            return '<img' . rtrim($attrs) . ' alt="' . $alt . '">';
        }, $html) ?? $html;

        $response->setContent($html);

        return $response;
    }
}
