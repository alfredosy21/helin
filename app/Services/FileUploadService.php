<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Exception;

class FileUploadService {

    /**
     * Save uploaded file with validation and processing
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function save(UploadedFile $file, string $directory, array $options = []): array {
        try {
            // Validate file
            $this->validateFile($file, $options);

            // Generate unique filename
            $filename = $this->generateFilename($file);

            // Create directory if it doesn't exist
            $fullDirectory = $this->ensureDirectoryExists($directory);

            // Process file (resize images, etc.)
            $processedFile = $this->processFile($file, $options);

            // Store file
            $path = $processedFile->storeAs($fullDirectory, $filename, 'public');

            // Generate thumbnail if it's an image
            $thumbnailPath = null;
            if ($this->isImage($file) && ($options['thumbnail'] ?? true)) {
                $thumbnailPath = $this->generateThumbnail($path, $filename, $fullDirectory, $options);
            }

            return [
                'success' => true,
                'path' => $path,
                'name' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'thumbnail' => $thumbnailPath,
                'url' => Storage::url($path),
                'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null
            ];
        } catch (Exception $e) {
            throw new Exception('Error al subir archivo: ' . $e->getMessage());
        }
    }

    /**
     * Delete file from storage
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                // Also try to delete thumbnail
                $thumbnailPath = $this->getThumbnailPath($path);
                if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get file information
     *
     * @param string $path
     * @return array|null
     */
    public function getFileInfo(string $path): ?array {
        try {
            if (!Storage::disk('public')->exists($path)) {
                return null;
            }

            $mimeType = Storage::disk('public')->mimeType($path);
            $size = Storage::disk('public')->size($path);
            $lastModified = Storage::disk('public')->lastModified($path);

            return [
                'path' => $path,
                'url' => Storage::url($path),
                'mime_type' => $mimeType,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'last_modified' => $lastModified,
                'last_modified_formatted' => date('Y-m-d H:i:s', $lastModified),
                'is_image' => $this->isImageMimeType($mimeType),
                'extension' => pathinfo($path, PATHINFO_EXTENSION)
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @param array $options
     * @throws Exception
     */
    private function validateFile(UploadedFile $file, array $options): void {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new Exception('Archivo no válido');
        }

        // Check file size
        $maxSize = $options['max_size'] ?? 10 * 1024 * 1024; // 10MB default
        if ($file->getSize() > $maxSize) {
            throw new Exception('El archivo excede el tamaño máximo permitido');
        }

        // Check allowed mime types
        $allowedMimes = $options['allowed_mimes'] ?? $this->getDefaultAllowedMimes();
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new Exception('Tipo de archivo no permitido');
        }

        // Check allowed extensions
        $allowedExtensions = $options['allowed_extensions'] ?? $this->getDefaultAllowedExtensions();
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new Exception('Extensión de archivo no permitida');
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateFilename(UploadedFile $file): string {
        $extension = $file->getClientOriginalExtension();
        $basename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('YmdHis');
        $random = Str::random(4);

        return "{$basename}-{$timestamp}-{$random}.{$extension}";
    }

    /**
     * Ensure directory exists
     *
     * @param string $directory
     * @return string
     */
    private function ensureDirectoryExists(string $directory): string {
        $fullDirectory = trim($directory, '/');

        if (!Storage::disk('public')->exists($fullDirectory)) {
            Storage::disk('public')->makeDirectory($fullDirectory);
        }

        return $fullDirectory;
    }

    /**
     * Process file (resize images, etc.)
     *
     * @param UploadedFile $file
     * @param array $options
     * @return UploadedFile
     */
    private function processFile(UploadedFile $file, array $options): UploadedFile {
        if (!$this->isImage($file)) {
            return $file;
        }

        $resizeOptions = $options['resize'] ?? null;
        if ($resizeOptions) {
            return $this->resizeImage($file, $resizeOptions);
        }

        return $file;
    }

    /**
     * Resize image
     *
     * @param UploadedFile $file
     * @param array $options
     * @return UploadedFile
     */
    private function resizeImage(UploadedFile $file, array $options): UploadedFile {
        $width = $options['width'] ?? 1200;
        $height = $options['height'] ?? null;
        $quality = $options['quality'] ?? 85;

        $image = Image::make($file->getPathname());

        if ($height) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'resized_');
        $image->save($tempPath, $quality);

        return new UploadedFile($tempPath, $file->getClientOriginalName(), $file->getMimeType(), null, true);
    }

    /**
     * Generate thumbnail for image
     *
     * @param string $originalPath
     * @param string $filename
     * @param string $directory
     * @param array $options
     * @return string|null
     */
    private function generateThumbnail(string $originalPath, string $filename, string $directory, array $options): ?string {
        try {
            $thumbnailSize = $options['thumbnail_size'] ?? 300;
            $thumbnailQuality = $options['thumbnail_quality'] ?? 75;

            $fullPath = Storage::disk('public')->path($originalPath);
            $image = Image::make($fullPath);

            // Create thumbnail
            $image->fit($thumbnailSize, $thumbnailSize, function ($constraint) {
                $constraint->upsize();
            });

            // Generate thumbnail filename
            $pathInfo = pathinfo($filename);
            $thumbnailFilename = $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

            // Save thumbnail
            $thumbnailPath = $directory . '/thumbnails/' . $thumbnailFilename;
            Storage::disk('public')->makeDirectory(dirname($thumbnailPath));

            $tempThumbnailPath = tempnam(sys_get_temp_dir(), 'thumb_');
            $image->save($tempThumbnailPath, $thumbnailQuality);

            Storage::disk('public')->put($thumbnailPath, file_get_contents($tempThumbnailPath));
            unlink($tempThumbnailPath);

            return $thumbnailPath;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get thumbnail path for original file
     *
     * @param string $originalPath
     * @return string|null
     */
    private function getThumbnailPath(string $originalPath): ?string {
        $pathInfo = pathinfo($originalPath);
        $directory = dirname($originalPath);
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        return $directory . '/thumbnails/' . $filename . '_thumb.' . $extension;
    }

    /**
     * Check if file is an image
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function isImage(UploadedFile $file): bool {
        return $this->isImageMimeType($file->getMimeType());
    }

    /**
     * Check if mime type is an image
     *
     * @param string $mimeType
     * @return bool
     */
    private function isImageMimeType(string $mimeType): bool {
        return in_array($mimeType, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ]);
    }

    /**
     * Format file size for display
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize(int $bytes): string {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Get default allowed mime types
     *
     * @return array
     */
    private function getDefaultAllowedMimes(): array {
        return [
            // Images
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            // Text
            'text/plain',
            'text/csv'
        ];
    }

    /**
     * Get default allowed extensions
     *
     * @return array
     */
    private function getDefaultAllowedExtensions(): array {
        return [
            // Images
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            // Documents
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            // Text
            'txt', 'csv'
        ];
    }

    /**
     * Get image dimensions
     *
     * @param string $path
     * @return array|null
     */
    public function getImageDimensions(string $path): ?array {
        try {
            if (!Storage::disk('public')->exists($path)) {
                return null;
            }

            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'aspect_ratio' => $image->width() / $image->height()
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Optimize image for web
     *
     * @param string $path
     * @param array $options
     * @return array
     */
    public function optimizeImage(string $path, array $options = []): array {
        try {
            if (!Storage::disk('public')->exists($path)) {
                throw new Exception('Archivo no encontrado');
            }

            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);

            $quality = $options['quality'] ?? 80;
            $format = $options['format'] ?? null;
            $maxWidth = $options['max_width'] ?? 1920;
            $maxHeight = $options['max_height'] ?? 1080;

            // Resize if needed
            if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                $image->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Convert format if needed
            if ($format && $format !== 'auto') {
                $extension = $format;
            } else {
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }

            // Save optimized image
            $pathInfo = pathinfo($path);
            $optimizedFilename = $pathInfo['filename'] . '_optimized.' . $extension;
            $optimizedPath = $pathInfo['dirname'] . '/optimized/' . $optimizedFilename;

            Storage::disk('public')->makeDirectory(dirname($optimizedPath));

            $tempPath = tempnam(sys_get_temp_dir(), 'optimized_');
            $image->save($tempPath, $quality, $extension === 'png' ? 'png' : 'jpg');

            Storage::disk('public')->put($optimizedPath, file_get_contents($tempPath));
            unlink($tempPath);

            $originalSize = Storage::disk('public')->size($path);
            $optimizedSize = Storage::disk('public')->size($optimizedPath);
            $compressionRatio = (($originalSize - $optimizedSize) / $originalSize) * 100;

            return [
                'success' => true,
                'original_path' => $path,
                'optimized_path' => $optimizedPath,
                'original_size' => $originalSize,
                'optimized_size' => $optimizedSize,
                'compression_ratio' => round($compressionRatio, 2),
                'size_reduction' => $this->formatFileSize($originalSize - $optimizedSize)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
