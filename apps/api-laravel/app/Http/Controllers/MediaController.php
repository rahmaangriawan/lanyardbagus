<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 24), 1), 100);

        $query = Media::latest();

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate($perPage);

        return response()->json([
            'data' => $media->items(),
            'links' => [
                'first' => $media->url(1),
                'last' => $media->url($media->lastPage()),
                'prev' => $media->previousPageUrl(),
                'next' => $media->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file'     => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $file     = $request->file('file');
        $mimeType = $file->getMimeType();

        $convertible = in_array($mimeType, [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp',
        ]);

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $baseName .= '-' . Str::random(5); // Add random suffix to avoid collisions

        try {
            if ($convertible && extension_loaded('gd')) {
                [$webpContent, $width, $height] = $this->toWebP($file->getRealPath(), $mimeType);

                $name = "{$baseName}.webp";
                $path = "media/{$name}";
                
                Storage::disk('public')->put($path, $webpContent);
                $url = asset("storage/{$path}");
                $size = strlen($webpContent);
            } else {
                $ext  = $file->getClientOriginalExtension();
                $name = "{$baseName}.{$ext}";
                $path = $file->storeAs('media', $name, 'public');
                $url  = asset("storage/{$path}");
                $size = $file->getSize();
                [$width, $height] = str_starts_with($mimeType, 'image/')
                    ? (@getimagesize($file->getRealPath()) ?: [null, null])
                    : [null, null];
            }

            $altText = $request->input('alt_text');
            if (empty($altText)) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $altText = ucwords(str_replace(['-', '_'], ' ', $originalName));
            }

            $media = Media::create([
                'file_name' => $convertible ? "{$baseName}.webp" : $file->getClientOriginalName(),
                'url'       => $url,
                'mime_type' => $convertible ? 'image/webp' : $mimeType,
                'width'     => $width ?? null,
                'height'    => $height ?? null,
                'size'      => $size,
                'alt_text'  => $altText,
            ]);

            return response()->json($media, 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Media upload failed: ' . $e->getMessage());
            return response()->json(['message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    private function toWebP(string $srcPath, string $mimeType): array
    {
        $image = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($srcPath),
            'image/png'               => @imagecreatefrompng($srcPath),
            'image/gif'               => @imagecreatefromgif($srcPath),
            'image/bmp'               => @imagecreatefrombmp($srcPath),
            default                   => false,
        };

        if (! $image) {
            throw new \RuntimeException('Gagal membaca file gambar. Pastikan format file valid.');
        }

        $width  = imagesx($image);
        $height = imagesy($image);

        // Resize if too large (max 1600px width)
        $maxWidth = 1600;
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
            $width = $newWidth;
            $height = $newHeight;
        } elseif (in_array($mimeType, ['image/png', 'image/gif'])) {
            $canvas = imagecreatetruecolor($width, $height);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
            imagecopy($canvas, $image, 0, 0, 0, 0, $width, $height);
            imagedestroy($image);
            $image = $canvas;
        }

        ob_start();
        imagewebp($image, null, 75);
        $content = ob_get_clean();
        imagedestroy($image);

        return [$content, $width, $height];
    }

    public function destroy(Media $medium): JsonResponse
    {
        $path = parse_url($medium->url, PHP_URL_PATH);
        $relative = ltrim(str_replace('/storage', '', $path), '/');
        Storage::disk('public')->delete($relative);

        $medium->delete();

        return response()->json(['ok' => true]);
    }
}
