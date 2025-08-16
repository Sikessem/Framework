<?php

declare(strict_types=1);

namespace Sikessem\Support;

use Illuminate\Http\UploadedFile;
use RuntimeException;

class Upload
{
    protected ?string $name = null;

    public function __construct(protected UploadedFile $file) {}

    /**
     * Create an Upload instance from an existing UploadedFile.
     */
    public static function from(UploadedFile $file): self
    {
        return new self($file);
    }

    /**
     * Create an Upload instance from a remote URL.
     * This method follows redirects to get the final file location.
     */
    public static function fromUrl(string $url): self
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $data = curl_exec($ch);

        if ($data === false) {
            throw new RuntimeException('Failed to download file from URL: '.curl_error($ch));
        }

        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        $tempPath = tempnam(sys_get_temp_dir(), 'uploads');
        file_put_contents($tempPath, $data);

        return self::from(
            new UploadedFile($tempPath, basename(parse_url($finalUrl, PHP_URL_PATH) ?: 'file'))
        );
    }

    /**
     * Set a custom filename for storage.
     */
    public function asName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Store the uploaded file in the given path and disk.
     */
    public function toPath(string $path, string $disk = 'public'): string|false
    {
        if ($this->name) {
            $storedPath = $this->file->storeAs($path, $this->name, $disk);
            $this->name = null;

            return $storedPath;
        }

        return $this->file->store($path, $disk);
    }
}
