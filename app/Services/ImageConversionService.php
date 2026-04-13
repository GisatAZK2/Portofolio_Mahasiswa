<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageConversionService
{
    public static function storeWebp(UploadedFile $file, string $directory, int $quality = 80): string
    {
        $sourcePath = $file->getRealPath();
        if (!$sourcePath) {
            return self::storeFallback($file, $directory);
        }

        $diskPath = trim($directory, '/') . '/' . Str::uuid() . '.webp';
        $image = self::createImageResource($sourcePath);

        if ($image && function_exists('imagewebp')) {
            $tempFile = tempnam(sys_get_temp_dir(), 'webp_');
            if ($tempFile === false) {
                return self::storeFallback($file, $directory);
            }

            imagesavealpha($image, true);
            $converted = imagewebp($image, $tempFile, $quality);
            imagedestroy($image);

            if ($converted && file_exists($tempFile)) {
                $contents = file_get_contents($tempFile);
                unlink($tempFile);
                if ($contents !== false) {
                    Storage::disk('public')->put($diskPath, $contents);
                    return $diskPath;
                }
            }
        }

        return self::storeFallback($file, $directory);
    }

    protected static function createImageResource(string $path)
    {
        $info = getimagesize($path);
        if (!$info) {
            return false;
        }

        switch ($info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                $resource = imagecreatefrompng($path);
                imagesavealpha($resource, true);
                return $resource;
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            default:
                return false;
        }
    }

    protected static function storeFallback(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.' . $file->extension();
        return Storage::disk('public')->putFileAs($directory, $file, $filename);
    }
}
