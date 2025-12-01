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

      $file = request()->file('file');

      if (!$file) {
        return response()->json([
          'paths' => [],
          'errors' => ['No file uploaded']
        ], 422);
      }

      try {
        // Generate unique filename in Livewire's pattern
        // Format: livewire-file:{originalName}|{hash}
        $hash = \Illuminate\Support\Str::random(20);
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Livewire uses this specific format
        $filename = 'livewire-file:' . $originalName . '|' . $hash;

        // Store the file
        $path = $file->storeAs($directory, $filename, $disk);

        if (!$path) {
          return response()->json([
            'paths' => [],
            'errors' => ['Failed to store file']
          ], 500);
        }

        // Return the response in Livewire's EXACT expected format
        return response()->json([
          'paths' => [$path],
          'errors' => []
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'paths' => [],
          'errors' => [$e->getMessage()]
        ], 500);
      }
    })->middleware(['web'])->name('livewire.upload-file');
  }
}
