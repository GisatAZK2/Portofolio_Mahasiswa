<?php

namespace Tests\Unit;

use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageConversionServiceTest extends TestCase
{
    public function test_it_converts_palette_gif_images_to_webp(): void
    {
        Storage::fake('public');

        $tempPath = tempnam(sys_get_temp_dir(), 'gif_');
        $image = imagecreate(10, 10);
        $white = imagecolorallocate($image, 255, 255, 255);
        $red = imagecolorallocate($image, 255, 0, 0);
        imagefill($image, 0, 0, $white);
        imagesetpixel($image, 0, 0, $red);

        imagegif($image, $tempPath);
        imagedestroy($image);

        $file = new UploadedFile($tempPath, 'palette.gif', 'image/gif', null, true);

        $path = ImageConversionService::storeWebp($file, 'photos');

        $this->assertNotEmpty($path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);

        @unlink($tempPath);
    }
}
