<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class CustomizerController extends Controller
{
    /** Public: upload mockup image to R2. */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . Str::random(5) . '.webp';
        
        // Always try to convert to WebP for optimization
        $fileContent = $this->toWebP($file->getRealPath(), $mimeType) ?: file_get_contents($file->getRealPath());
        $mimeType = 'image/webp';

        try {
            $path = 'temp/' . $name;
            $url = $this->uploadToR2($path, $fileContent, $mimeType);
            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            Log::error('R2 temp upload failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to upload file to cloud storage. Please try again.',
            ], 502);
        }
    }

    /** Public: capture a lead with optional design file/url. */
    public function storeLead(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:191'],
            'whatsapp' => ['required', 'string', 'max:50'],
            'address'  => ['nullable', 'string', 'max:500'],
            'size'     => ['nullable', 'string', 'max:50'],
            'email'    => ['nullable', 'email', 'max:191'],
            'message'  => ['nullable', 'string', 'max:2000'],
            'file'     => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $data = $request->only(['name', 'whatsapp', 'address', 'size', 'email', 'message']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();
            $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . Str::random(8) . '.webp';
            
            $fileContent = $this->toWebP($file->getRealPath(), $mimeType) ?: file_get_contents($file->getRealPath());
            
            try {
                $path = 'leads/' . $name;
                $data['design_url'] = $this->uploadToR2($path, $fileContent, 'image/webp');
            } catch (\Exception $e) {
                Log::error('R2 lead upload failed: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Failed to upload file to cloud storage. Please try again.',
                ], 502);
            }
        }

        $lead = Lead::create($data);

        return response()->json([
            'id'      => $lead->id,
            'message' => 'Order received successfully',
        ], 201);
    }

    private function uploadToR2(string $path, string $content, string $mimeType): string
    {
        $config = $this->r2Config();

        if (!$config['key'] || !$config['secret'] || !$config['bucket'] || !$config['endpoint']) {
            throw new RuntimeException('R2 credentials incomplete');
        }

        config([
            'filesystems.disks.r2.key' => $config['key'],
            'filesystems.disks.r2.secret' => $config['secret'],
            'filesystems.disks.r2.bucket' => $config['bucket'],
            'filesystems.disks.r2.endpoint' => $config['endpoint'],
            'filesystems.disks.r2.url' => $config['public_url'],
            'filesystems.disks.r2.region' => 'auto',
            'filesystems.disks.r2.use_path_style_endpoint' => true,
            'filesystems.disks.r2.throw' => true,
        ]);

        $disk = Storage::disk('r2');

        $disk->put($path, $content, [
            'visibility' => 'public',
            'ContentType' => $mimeType,
        ]);

        return $disk->url($path);
    }

    private function r2Config(): array
    {
        $rawEndpoint = trim((string) Setting::get('r2_endpoint'));
        $endpoint = $rawEndpoint;
        if ($endpoint !== '' && !preg_match('#^https?://#i', $endpoint)) {
            $endpoint = 'https://' . trim($endpoint, '/') . '.r2.cloudflarestorage.com';
        }

        $rawPublicUrl = trim((string) Setting::get('r2_public_url'));
        $publicUrl = $rawPublicUrl;
        if ($publicUrl !== '' && !preg_match('#^https?://#i', $publicUrl)) {
            $publicUrl = 'https://' . trim($publicUrl, '/');
        }

        return [
            'key' => trim((string) Setting::get('r2_access_key_id')),
            'secret' => trim((string) Setting::get('r2_secret_access_key')),
            'bucket' => trim((string) Setting::get('r2_bucket_name')),
            'endpoint' => rtrim($endpoint, '/'),
            'public_url' => rtrim($publicUrl, '/'),
        ];
    }

    private function toWebP(string $srcPath, string $mimeType): ?string
    {
        $image = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($srcPath),
            'image/png'               => @imagecreatefrompng($srcPath),
            default                   => null,
        };

        if (!$image) return null;

        $width = imagesx($image);
        $height = imagesy($image);

        if ($mimeType === 'image/png') {
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
        imagewebp($image, null, 80);
        $content = ob_get_clean();
        imagedestroy($image);

        return $content;
    }

    /** Admin: list all leads (newest first). */
    public function index(): JsonResponse
    {
        return response()->json(Lead::latest()->get());
    }

    /** Admin: delete a lead. */
    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();
        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
