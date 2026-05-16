<?php

if (! function_exists('image_url')) {
    function image_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return asset('storage/' . $path);
    }
}
