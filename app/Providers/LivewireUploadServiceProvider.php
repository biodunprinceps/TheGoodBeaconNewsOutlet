<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LivewireUploadServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   * 
   * Override Livewire's upload route to bypass signature validation.
   */
  public function boot(): void
  {
    // Override Livewire's upload-file route with our own that doesn't check signatures
    Route::post('/livewire/upload-file', function () {
      $disk = config('livewire.temporary_file_upload.disk', 'public');
      $directory = config('livewire.temporary_file_upload.directory', 'livewire-tmp');

      // Try different possible file field names
      $files = request()->file('file')
        ?? request()->file('files')
        ?? request()->file('0');

      if (!$files) {
        return response()->json([
          'paths' => [],
          'errors' => ['No file uploaded - received fields: ' . implode(', ', array_keys(request()->all()))]
        ], 422);
      }

      // Handle both single file and array of files
      if (!is_array($files)) {
        $files = [$files];
      }

      try {
        $paths = [];

        foreach ($files as $file) {
          // Generate unique filename using only ASCII characters
          $hash = \Illuminate\Support\Str::random(40);

          // Get extension and ensure it's ASCII-safe
          $extension = $file->getClientOriginalExtension();
          $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
          if (empty($extension)) {
            $extension = 'tmp';
          }

          // Use simple ASCII-only filename format
          $filename = $hash . '.' . strtolower($extension);

          // Store the file
          $path = $file->storeAs($directory, $filename, $disk);
          if (!$path) {
            return response()->json([
              'paths' => [],
              'errors' => ['Failed to store file']
            ], 500);
          }

          $paths[] = $path;
        }

        // Return the response in Livewire's EXACT expected format
        // Use JSON_UNESCAPED_UNICODE to prevent encoding issues
        return response()->json([
          'paths' => $paths,
          'errors' => []
        ], 200, [], JSON_UNESCAPED_SLASHES);
      } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        return response()->json([
          'paths' => [],
          'errors' => ['Upload failed']
        ], 500);
      }
    })->middleware(['web'])->name('livewire.upload-file');
  }
}
