<?php

declare(strict_types=1);

use App\Exceptions\InsufficientBalanceException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('exceptions.not_found_message'),
                ], 404);
            }

            return view('errors.404');
        });
        $exceptions->render(function (InsufficientBalanceException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('exceptions.insufficient_funds_message'),
                ], 404);
            }

            return view('errors.404');
        });
    })->create();
