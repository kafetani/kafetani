<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

if (! function_exists('asset_v')) {
    /**
     * Generate an asset URL with a cache-busting version query string.
     *
     * The version is a hash of the file's actual content (md5_file), so the
     * URL only changes when the content itself changes (e.g.
     * style-app.css?v=a1b2c3d4) — not just because the file was re-copied or
     * touched during a deploy. This forces Cloudflare (and browsers) to fetch
     * the new file instead of serving a stale cached copy, even with an
     * aggressive "Cache Everything" page rule, while avoiding unnecessary
     * cache invalidation when nothing actually changed.
     *
     * Falls back to the plain asset() URL (no query string) if the file
     * cannot be found, so it never breaks a page if a path is wrong.
     */
    function asset_v(string $path, bool $secure = null): string
    {
        $fullPath = public_path(ltrim($path, '/'));

        if (File::exists($fullPath)) {
            $mtime = File::lastModified($fullPath);

            // Cache the content hash per (path, mtime). mtime is only used as
            // a cheap "did this file possibly change?" check to decide
            // whether to re-hash — the version itself is still the file's
            // actual content hash, so it never changes unless content does.
            $version = Cache::rememberForever(
                "asset_v:{$path}:{$mtime}",
                fn () => substr(md5_file($fullPath), 0, 8)
            );

            return asset($path, $secure) . '?v=' . $version;
        }

        return asset($path, $secure);
    }
}
