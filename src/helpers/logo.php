<?php

declare(strict_types=1);

/**
 * Resolve logo: coloque o arquivo em assets/img/ como logo.png, logo.webp ou logo.svg
 *
 * @return array{path: ?string, exists: bool}
 */
function bk_logo_asset(): array
{
    $dir = dirname(__DIR__, 2) . '/assets/img';
    foreach (['logo.webp', 'logo.png', 'logo.svg', 'logo.jpg'] as $file) {
        $full = $dir . '/' . $file;
        if (is_readable($full)) {
            return ['path' => '../assets/img/' . $file, 'exists' => true];
        }
    }
    return ['path' => null, 'exists' => false];
}
