<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\SetLocale::class);
        $middleware->append(\App\Http\Middleware\EnsureImageAlts::class);
        $middleware->append(\App\Http\Middleware\SetRobotsHeader::class);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('yonetim') || $request->is('yonetim/*')) {
                return route('yonetim.login');
            }

            $lang = app()->getLocale() ?: 'tr';

            return url('/' . $lang . '/giris');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $render404 = function ($request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not Found'], 404);
            }

            return response()->view('errors.404', [], 404);
        };

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) use ($render404) {
            return $render404($request);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) use ($render404) {
            return $render404($request);
        });

        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            $limit = ini_get('post_max_size') ?: '8M';
            $message = "Gönderilen veri çok büyük. Toplam yükleme limiti: {$limit}. Lütfen daha küçük dosyalar seçin veya görselleri tek tek yükleyin.";

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            return redirect()->back()->withInput()->withErrors(['upload' => $message]);
        });
    })->create();
