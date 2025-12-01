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
   * 
   * NOTE: This method should return ONLY the basename WITHOUT extension.
   * Spatie will automatically append the extension from the original file.
   */
  public function originalFileName(string $fileName): string
  {
    // Return just the UUID without any extension
    // Spatie Media Library will automatically add the extension
    return (string) Str::uuid();
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
