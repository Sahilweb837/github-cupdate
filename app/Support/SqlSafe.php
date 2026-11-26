<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class SqlSafe
{
    /**
     * Escape SQL LIKE wildcard characters (%, _, \) so user search input
     * cannot widen pattern matching or trigger full-table wildcard scans.
     */
    public static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($value));
    }

    /**
     * Resolve a safe, server-verified image extension from MIME inspection
     * rather than trusting the client-supplied filename extension.
     */
    public static function safeImageExtension(UploadedFile $file): string
    {
        $ext = strtolower((string) $file->guessExtension());

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? $ext : 'jpg';
    }
}
