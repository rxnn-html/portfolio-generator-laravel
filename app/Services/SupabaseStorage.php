<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

// Uploads and deletes profile pictures in the Supabase Storage bucket.
// It uses the public (publishable) key, so the bucket policies decide what is allowed.
class SupabaseStorage
{
    private string $url;
    private string $key;
    private string $bucket;

    public function __construct()
    {
        $this->url = rtrim((string) config('supabase.url'), '/');
        $this->key = (string) config('supabase.key');
        $this->bucket = (string) config('supabase.bucket');
    }

    private function headers(): array
    {
        return ['apikey' => $this->key, 'Authorization' => 'Bearer '.$this->key];
    }

    // Returns the public URL of the uploaded file
    public function upload(UploadedFile $file): string
    {
        $mime = $file->getMimeType();
        $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;

        if (! $extension) {
            throw new RuntimeException('Unsupported image type.');
        }

        // The file name is made by us, never taken from the user
        $name = Str::uuid().'.'.$extension;

        $response = Http::withHeaders($this->headers())
            ->withBody(file_get_contents($file->getRealPath()), $mime)
            ->post("{$this->url}/storage/v1/object/{$this->bucket}/{$name}");

        if ($response->failed()) {
            throw new RuntimeException('Image upload failed: '.$response->body());
        }

        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$name}";
    }

    // Deletes a file from its public URL. A failure is logged but never stops the request.
    public function delete(?string $publicUrl): void
    {
        $path = $this->pathFromUrl($publicUrl);
        if (! $path) {
            return;
        }

        try {
            Http::withHeaders($this->headers())
                ->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");
        } catch (Throwable $e) {
            report($e);
        }
    }

    // ".../profile-images/abc.jpg" -> "abc.jpg" (null for anything that is not one of our files)
    private function pathFromUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $marker = "/{$this->bucket}/";
        $position = strpos($url, $marker);
        if ($position === false) {
            return null;
        }

        $path = explode('?', substr($url, $position + strlen($marker)))[0];

        return ($path !== '' && ! str_contains($path, '/')) ? $path : null;
    }
}