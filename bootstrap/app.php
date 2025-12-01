<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add middleware to sanitize Livewire uploads and catch errors
        $middleware->append(\App\Http\Middleware\SanitizeLivewireUploads::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions with ASCII-safe error messages to avoid UTF-8 issues
        $exceptions->report(function (Throwable $e) {
            // Get a safe error message
            $message = $e->getMessage();

            // If message contains non-ASCII, sanitize it
            if (!mb_check_encoding($message, 'ASCII')) {
                $message = mb_convert_encoding($message, 'ASCII', 'UTF-8');
                $message = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $e->getMessage());
            }

            Log::error('Exception caught: ' . get_class($e), [
                'message' => $message,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
            ]);
        });

        // Custom rendering to prevent UTF-8 errors in error pages
        $exceptions->render(function (Throwable $e, $request) {
            // For Livewire/AJAX requests, return JSON with ASCII-safe message
            if ($request->wantsJson() || $request->header('X-Livewire')) {
                $message = $e->getMessage();

                // Sanitize message to ASCII
                if (!mb_check_encoding($message, 'ASCII')) {
                    $message = 'An error occurred. Please check the logs for details.';
                }

                return response()->json([
                    'message' => $message,
                    'error' => get_class($e),
                ], 500);
            }

            return null; // Let Laravel handle other responses
        });
    })->create();
