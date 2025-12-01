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
        return response()->json(['error' => 'No file uploaded'], 422);
      }

      // Generate unique filename
      $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

      // Store the file
      $path = $file->storeAs($directory, $filename, $disk);

      // Return the response in Livewire's EXACT expected format
      return response()->json([
        'paths' => [$path],  // Livewire expects 'paths' array, not 'path'
        'errors' => [],      // Required by Livewire
      ]);
    })->middleware(['web'])->name('livewire.upload-file');
  }
}
