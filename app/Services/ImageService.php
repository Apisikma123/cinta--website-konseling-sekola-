<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Upload and compress image to target size
     *
     * @param UploadedFile $file
     * @param int $maxSizeKB Maximum file size in KB (default 1024 = 1MB)
     * @return string Path to stored image
     */
    public function uploadAndCompress(UploadedFile $file, int $maxSizeKB = 1024): string
    {
        try {
            // Generate unique filename with original extension if safe
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                $extension = 'jpg';
            }
            $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            
            // Load image
            $image = Image::read($file);
            
            // Get original file size in KB
            $originalSize = $file->getSize() / 1024;
            
            // If file is already under max size and not too large dimensions, just save it
            if ($originalSize <= $maxSizeKB && $image->width() <= 2000) {
                $path = 'profile_photos/' . $filename;
                // Still encode to Jpeg to ensure consistency and strip metadata
                $encoded = $image->toJpeg(85);
                Storage::disk('public')->put($path, (string)$encoded);
                return $path;
            }
            
            // Compress image iteratively
            if ($image->width() > 1200) {
                $image->scale(width: 1200); // Max width for profile photo
            }

            $quality = 80;
            $encoded = $image->toJpeg($quality);
            
            while (strlen((string)$encoded) / 1024 > $maxSizeKB && $quality > 10) {
                $quality -= 10;
                $encoded = $image->toJpeg($quality);
            }
            
            // Final fallback: aggressive resize
            if (strlen((string)$encoded) / 1024 > $maxSizeKB) {
                $image->scale(width: 600);
                $encoded = $image->toJpeg(60);
            }
            
            // Save compressed image
            $path = 'profile_photos/' . $filename;
            Storage::disk('public')->put($path, (string)$encoded);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());
            // Fallback: save original if compression fails
            $filename = 'raw_' . time() . '_' . $file->getClientOriginalName();
            return Storage::disk('public')->putFileAs('profile_photos', $file, $filename);
        }
    }
    
    /**
     * Delete old image from storage
     *
     * @param string|null $path
     * @return bool
     */
    public function deleteOldImage(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
    
    /**
     * Get full URL for image path
     *
     * @param string|null $path
     * @return string
     */
    public function getImageUrl(?string $path): string
    {
        if ($path) {
            return Storage::disk('public')->url($path);
        }
        
        return asset('images/default-avatar.png');
    }
}
