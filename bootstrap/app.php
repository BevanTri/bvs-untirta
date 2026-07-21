<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$sessionPath = __DIR__ . '/../storage/framework/sessions';
if (!is_dir($sessionPath)) { @mkdir($sessionPath, 0777, true); @chmod($sessionPath, 0777); }
if (is_writable($sessionPath)) {
$_ENV['SESSION_DRIVER'] = 'file';
$_SERVER['SESSION_DRIVER'] = 'file';
$_ENV['SESSION_SAVE_PATH'] = $sessionPath;
$_SERVER['SESSION_SAVE_PATH'] = $sessionPath;
} else {
$_ENV['SESSION_DRIVER'] = 'database';
$_SERVER['SESSION_DRIVER'] = 'database';
}


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\DetectAppUrl::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            //
        ]);
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
