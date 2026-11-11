<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isApi = fn (Request $request) => $request->is('api/*') || $request->expectsJson();

        $exceptions->shouldRenderJsonWhen($isApi);

        // Pesan error API yang konsisten dan berbahasa Indonesia.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json(['message' => 'Data atau endpoint tidak ditemukan.'], 404);
            }
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json(['message' => 'Metode HTTP tidak diizinkan untuk endpoint ini.'], 405);
            }
        });
        $exceptions->render(function (AuthenticationException $e, Request $request) use ($isApi) {
            if ($isApi($request)) {
                return response()->json(['message' => 'Tidak terautentikasi. Sertakan header Authorization: Bearer <token>.'], 401);
            }
        });
        $exceptions->render(function (QueryException $e, Request $request) use ($isApi) {
            // Pelanggaran foreign key / unique yang lolos validasi (mis. race condition).
            if ($isApi($request) && in_array($e->errorInfo[0] ?? null, ['23000', '23503', '23505'], true)) {
                return response()->json(['message' => 'Operasi ditolak karena melanggar relasi atau keunikan data.'], 409);
            }
        });
    })->create();
