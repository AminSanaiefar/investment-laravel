<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function saveImage(
        UploadedFile $image,
        string $destPath,
        ?array $imageSize = null,
        int $quality = 85,
        ?string $fileName = null,
        string $format = 'webp'
    ): string {

        if (!File::exists(public_path($destPath))) {

            File::makeDirectory(
                public_path($destPath),
                0755,
                true
            );
        }

        $fileName = $fileName
            ?? Str::uuid() . '.' . $format;

        $fullPath = public_path(
            $destPath . '/' . $fileName
        );

        $img = Image::decode($image);

        if ($imageSize) {
            $width = $imageSize['width'] ?? null;
            $height = $imageSize['height'] ?? null;

            $img->scale(
                width: $width,
                height: $height
            );
        }

        // ذخیره
        $img->save(
            $fullPath,
            quality: $quality
        );

        return $destPath . '/' . $fileName;
    }

    public function deleteImage(string $destPath): bool
    {
        $absolutePath = public_path($destPath);

        if (File::exists($absolutePath)) {
            return File::delete($absolutePath);
        }

        return false;
    }
}