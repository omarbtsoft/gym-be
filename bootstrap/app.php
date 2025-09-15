<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $e) {
            logger()->error('Error capturado', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
        });

        $exceptions->render(function (Throwable $e, $request) {

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {

                return response()->json([
                    'error1' => 'Ruta no encontrada',
                    'url'     => $request->fullUrl(),
                    'method'  => $request->method(),
                    'headers' => $request->headers->all(),
                    'query'   => $request->query(),
                    'body'    => $request->all(),
                    'ip'      => $request->ip(),
                    'error'   => $e->getMessage()
                ], 404);
            }

            return response()->json([
                'error' => 'Ha ocurrido un error en el servidor',
                'message' => $e->getMessage(),
            ], 500);
        });
    })->create();
