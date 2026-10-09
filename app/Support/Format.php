<?php

namespace App\Support;

class Format
{
    // "Juan Dela Cruz" -> "JD"
    public static function initials(?string $name): string
    {
        $words = array_slice(preg_split('/\s+/', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY), 0, 2);

        return mb_strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), $words)));
    }

    // "React, Node.js" -> ['React', 'Node.js']
    public static function splitList(?string $text): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $text))));
    }

    // "Jan 2024" + "Present" -> "Jan 2024 â€“ Present"
    public static function dateRange(?string $start, ?string $end): string
    {
        if ($start && $end) return "{$start} â€“ {$end}";
        if ($start) return "{$start} â€“ Present";

        return (string) $end;
    }

    // "https://github.com/juan/" -> "github.com/juan"
    public static function displayUrl(?string $url): string
    {
        $parts = parse_url((string) $url);

        return rtrim(($parts['host'] ?? (string) $url).($parts['path'] ?? ''), '/');
    }

    // Returns the URL only if it starts with http:// or https:// (blocks "javascript:" links)
    public static function safeUrl(?string $url): ?string
    {
        return ($url && preg_match('#^https?://#i', $url) && filter_var($url, FILTER_VALIDATE_URL)) ? $url : null;
    }
}