<?php

declare(strict_types=1);

namespace App\Services;

class UploadService
{
    private string $basePath;
    private int $maxSize;
    private array $allowedTypes;

    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 2) . '/storage/uploads';
        $this->maxSize = 5 * 1024 * 1024; // 5MB
        $this->allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ];
    }

    public function upload(string $fieldName, string $subfolder): ?string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fieldName];

        if ($file['size'] > $this->maxSize) {
            throw new \RuntimeException('La imagen no puede superar los 5MB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedTypes, true)) {
            throw new \RuntimeException('Tipo de archivo no permitido. Use JPG, PNG, GIF o WebP.');
        }

        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
            default      => 'jpg',
        };

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $destDir = $this->basePath . '/' . $subfolder;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $destPath = $destDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new \RuntimeException('Error al guardar la imagen.');
        }

        return $subfolder . '/' . $filename;
    }

    public function delete(?string $relativePath): bool
    {
        if ($relativePath === null || $relativePath === '') {
            return false;
        }

        $fullPath = $this->basePath . '/' . $relativePath;

        if (is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}
