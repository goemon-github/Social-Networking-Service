<?php
namespace src\helpers;
use RuntimeException;

function asset_path(string $entry, string $type = 'file'): string
{
    static $manifest;
    $manifest ??= json_decode(
        file_get_contents(dirname(__DIR__) . '/frontend/dist/.vite/manifest.json'),
        true
    );

    if (!isset($manifest[$entry][$type])) {
        throw new RuntimeException("Asset {$entry} not found in manifest");
    }
    return '/assets/' . $manifest[$entry][$type];
}
