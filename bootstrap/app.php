<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SecurityHeaders;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

if (getenv('APP_STORAGE')) {
    $app->useStoragePath(getenv('APP_STORAGE'));
}

// This project keeps its Laravel configuration files in the repository. Avoid
// merging the framework defaults, which are loaded from vendor at runtime.
$app->dontMergeFrameworkConfiguration();

return $app;
