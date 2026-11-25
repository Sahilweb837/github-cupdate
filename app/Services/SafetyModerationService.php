<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class SafetyModerationService
{
    /**
     * Explicit solicitation, nudity, scam, and spam terms screened before publishing.
     *
     * @var array<int, string>
     */
    private const BLOCKED_PATTERNS = [
        '/\b(nude|nudes|naked|porn|porno|xxx|sexchat|camgirl|escort|onlyfans|stripchat|chaturbate|nsfw)\b/i',
        '/\b(send\s+nude|show\s+boobs|show\s+body|paid\s+sex|hookup\s+for\s+money|sugar\s+daddy|sugar\s+baby)\b/i',
        '/\b(crypto\s+invest|forex\s+signal|guaranteed\s+profit|loan\s+approval|click\s+this\s+link)\b/i',
    ];

    /**
     * Patterns that do not auto-block but flag content for moderator review.
     *
     * @var array<int, string>
     */
    private const FLAG_PATTERNS = [
        '/(https?:\/\/|www\.)\S+/i',
        '/\b(telegram|t\.me|whatsapp\s*me|snapchat|insta\s*id|dm\s*me\s*on)\b/i',
        '/(\+?\d[\d\s\-]{8,}\d)/',
    ];

    /**
     * Screen user-supplied text for spam, nudity solicitation, and abuse.
     *
     * @return array{allowed: bool, flagged: bool, reason: string|null}
     */
    public function screenText(?string $text): array
    {
        $content = trim((string) $text);
        if ($content === '') {
            return ['allowed' => true, 'flagged' => false, 'reason' => null];
        }

        foreach (self::BLOCKED_PATTERNS as $pattern) {
            if (preg_match($pattern, $content)) {
                return [
                    'allowed' => false,
                    'flagged' => true,
                    'reason' => 'Content violates CupDate no-nudity, no-spam community safety policy.',
                ];
            }
        }

        // Reject excessive repeated characters or URL-only spam floods
        if (preg_match('/(.)\1{14,}/u', $content) || substr_count(strtolower($content), 'http') >= 2) {
            return [
                'allowed' => false,
                'flagged' => true,
                'reason' => 'Repeated characters or multiple external links are blocked as spam.',
            ];
        }

        foreach (self::FLAG_PATTERNS as $pattern) {
            if (preg_match($pattern, $content)) {
                return [
                    'allowed' => true,
                    'flagged' => true,
                    'reason' => 'External handle or link flagged for safety review.',
                ];
            }
        }

        return ['allowed' => true, 'flagged' => false, 'reason' => null];
    }

    /**
     * Validate uploaded image integrity, format, dimensions, and basic skin-exposure safety heuristic.
     *
     * @return array{allowed: bool, flagged: bool, reason: string|null}
     */
    public function screenImage(UploadedFile $file): array
    {
        $realPath = $file->getRealPath();
        if (! $realPath || ! is_file($realPath)) {
            return ['allowed' => false, 'flagged' => true, 'reason' => 'Invalid image upload.'];
        }

        $info = @getimagesize($realPath);
        if (! is_array($info)) {
            return ['allowed' => false, 'flagged' => true, 'reason' => 'File is not a valid image.'];
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $mime = $info['mime'] ?? '';
        if (! in_array($mime, $allowedMimes, true)) {
            return ['allowed' => false, 'flagged' => true, 'reason' => 'Only JPEG, PNG, and WEBP images are allowed.'];
        }

        [$width, $height] = [$info[0] ?? 0, $info[1] ?? 0];
        if ($width < 40 || $height < 40 || $width > 6000 || $height > 6000) {
            return ['allowed' => false, 'flagged' => true, 'reason' => 'Image dimensions are outside safe limits (40px–6000px).'];
        }

        $flagged = $this->estimateExcessiveSkinRatio($realPath, $mime) > 0.68;

        return [
            'allowed' => true,
            'flagged' => $flagged,
            'reason' => $flagged ? 'Image queued for moderator safety review.' : null,
        ];
    }

    /**
     * Lightweight GD pixel sampler to flag high-skin-exposure uploads for moderator review.
     */
    private function estimateExcessiveSkinRatio(string $path, string $mime): float
    {
        if (! function_exists('imagecreatefromjpeg')) {
            return 0.0;
        }

        $img = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : false,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if (! $img) {
            return 0.0;
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $stepX = max(1, intdiv($w, 24));
        $stepY = max(1, intdiv($h, 24));

        $samples = 0;
        $skinHits = 0;

        for ($y = 0; $y < $h; $y += $stepY) {
            for ($x = 0; $x < $w; $x += $stepX) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $samples++;
                if ($r > 95 && $g > 40 && $b > 20 && (max($r, $g, $b) - min($r, $g, $b)) > 15 && abs($r - $g) > 15 && $r > $g && $r > $b) {
                    $skinHits++;
                }
            }
        }

        if (function_exists('imagedestroy')) {
            @imagedestroy($img);
        }

        return $samples > 0 ? ($skinHits / $samples) : 0.0;
    }
}
