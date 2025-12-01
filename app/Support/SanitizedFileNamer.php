<?php

namespace App\Support;

use Spatie\MediaLibrary\Support\FileNamer\FileNamer;
use Spatie\MediaLibrary\Conversions\Conversion;
use Illuminate\Support\Str;

class SanitizedFileNamer extends FileNamer
{
  /**
   * Generate an ASCII-safe filename for the original file.
   * Uses UUID to avoid any encoding issues with PostgreSQL.
   */
  public function originalFileName(string $fileName): string
  {
    // Get the extension
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Clean the extension to be ASCII-safe
    $extension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
    $extension = strtolower($extension);

    // Generate a unique UUID filename
    $uuid = (string) Str::uuid();

    // Return with extension if available
    if (!empty($extension)) {
      return $uuid . '.' . $extension;
    }

    // Fallback if no extension
    return $uuid;
  }

  /**
   * Generate filename for image conversions.
   */
  public function conversionFileName(string $fileName, Conversion $conversion): string
  {
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    return $baseName . '-' . $conversion->getName() . '.' . $extension;
  }

  /**
   * Generate filename for responsive images.
   */
  public function responsiveFileName(string $fileName): string
  {
    return $fileName;
  }
}
