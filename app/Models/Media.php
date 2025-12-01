<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
  /**
   * Boot the model and add observers to sanitize data before saving.
   */
  protected static function boot()
  {
    parent::boot();

    // Sanitize filename and name before saving to prevent UTF-8 encoding errors
    static::saving(function ($media) {
      // Sanitize file_name - remove any non-ASCII characters
      if ($media->file_name) {
        $media->file_name = self::sanitizeToAscii($media->file_name);
      }

      // Sanitize name - convert to valid UTF-8 or ASCII
      if ($media->name) {
        $media->name = self::sanitizeToValidUtf8($media->name);
      }

      // Ensure custom_properties is JSON-safe
      if ($media->custom_properties && is_array($media->custom_properties)) {
        $media->custom_properties = self::sanitizeArray($media->custom_properties);
      }
    });
  }

  /**
   * Sanitize a string to ASCII-only characters.
   * This is aggressive and removes/replaces anything non-ASCII.
   */
  protected static function sanitizeToAscii(string $value): string
  {
    // First, try to convert to ASCII using transliteration
    $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

    if ($ascii === false || empty($ascii)) {
      // If conversion failed, use regex to keep only ASCII characters
      $ascii = preg_replace('/[^\x20-\x7E]/', '', $value);
    }

    // If still empty, generate a safe fallback
    if (empty($ascii)) {
      $ascii = 'file_' . time() . '_' . rand(1000, 9999);
    }

    return $ascii;
  }

  /**
   * Sanitize a string to valid UTF-8.
   * Less aggressive than sanitizeToAscii - keeps valid UTF-8 characters.
   */
  protected static function sanitizeToValidUtf8(string $value): string
  {
    // Remove invalid UTF-8 sequences
    $sanitized = mb_convert_encoding($value, 'UTF-8', 'UTF-8');

    // Double-check it's valid
    if (!mb_check_encoding($sanitized, 'UTF-8')) {
      // Fall back to ASCII-only
      return self::sanitizeToAscii($value);
    }

    return $sanitized;
  }

  /**
   * Recursively sanitize an array to ensure all strings are UTF-8 safe.
   */
  protected static function sanitizeArray(array $array): array
  {
    foreach ($array as $key => $value) {
      if (is_string($value)) {
        $array[$key] = self::sanitizeToValidUtf8($value);
      } elseif (is_array($value)) {
        $array[$key] = self::sanitizeArray($value);
      }
    }

    return $array;
  }
}
