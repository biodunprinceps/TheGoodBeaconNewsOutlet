<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SanitizeLivewireUploads
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    // Check if this is a Livewire upload request
    if ($request->hasHeader('X-Livewire') || $request->is('livewire/*')) {
      try {
        // Process the request
        $response = $next($request);

        // If response is JSON, ensure it's UTF-8 safe
        if ($response instanceof \Illuminate\Http\JsonResponse) {
          $data = $response->getData(true);

          // Check if data can be JSON encoded
          $testEncode = @json_encode($data);
          if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON encoding error in Livewire response', [
              'error' => json_last_error_msg(),
              'data_keys' => array_keys($data),
            ]);

            // Return a safe error response
            return response()->json([
              'message' => 'An error occurred processing your request. Please try again.',
              'error' => 'encoding_error',
            ], 500);
          }
        }

        return $response;
      } catch (\Throwable $e) {
        Log::error('Error in SanitizeLivewireUploads middleware', [
          'message' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
          'trace' => $e->getTraceAsString(),
        ]);

        // Return a safe JSON error
        return response()->json([
          'message' => 'An error occurred. Please check the logs.',
          'error' => get_class($e),
        ], 500);
      }
    }

    return $next($request);
  }
}
