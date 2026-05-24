<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

/**
 * Helpers Utility Class
 *
 * Essential utility functions for network identification and
 * database-friendly string transformations.
 *
 * @package App\Utils
 * @author  Helin Latam Development Team
 * @version 1.0.0
 */
class Helpers {

    /**
     * Get client IP address with proxy and Cloudflare support.
     *
     * Validates and extracts the real client IP by checking common proxy headers
     * and filtering out private/reserved network ranges.
     *
     * @return string The validated client IP address
     */
    public static function getIp(): string {
        $ipAddresses = [];

        // Check for various IP headers in order of preference
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR', // Load balancers, proxies
            'HTTP_X_REAL_IP', // Nginx
            'HTTP_CLIENT_IP', // Some proxies
            'REMOTE_ADDR'               // Default
        ];

        foreach ($ipHeaders as $header) {
            $ip = Request::server($header);

            if ($ip && !empty($ip)) {
                // X_FORWARDED_FOR can contain multiple IPs, get the first one (client)
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // Validate IP (exclude private and reserved ranges for security)
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    $ipAddresses[] = $ip;
                }
            }
        }

        // Return first valid public IP or fallback to Laravel's default detection
        return !empty($ipAddresses) ? $ipAddresses[0] : Request::ip();
    }

    /**
     * Generate a unique slug for a specific database table.
     *
     * Converts a string to a URL-friendly slug and checks against the database
     * to ensure uniqueness, appending a counter if the slug already exists.
     *
     * @param string $string The source string to convert
     * @param string $table The database table name to check for uniqueness
     * @param int|null $excludeId Optional ID to ignore (useful for updates)
     * @return string The generated unique slug
     */
    public static function generateSlug(string $string, string $table, ?int $excludeId = null): string {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = DB::table($table)->where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            // Append counter if slug is already taken
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate unique image filename with timestamp and random string.
     *
     * Creates a unique filename to prevent duplicates while maintaining
     * the original file extension for proper MIME type handling.
     *
     * @param UploadedFile $file The uploaded file instance
     * @param string $prefix Optional prefix for the filename (e.g., 'product', 'blog', 'testimonial')
     * @return string The generated unique filename
     */
    public static function generateImageName(UploadedFile $file, string $prefix = 'img'): string {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);

        return sprintf('%s-%s-%s.%s', $prefix, $timestamp, $random, $extension);
    }
}
