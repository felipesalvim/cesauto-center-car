<?php

declare(strict_types=1);

/**
 * Carrega config/.env e expõe helpers de configuração.
 * O arquivo .env real NÃO deve ser versionado.
 */

$root = dirname(__DIR__);

if (!function_exists('bk_load_env')) {
    function bk_load_env(string $path): void
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            // Remove aspas simples/duplas opcionais
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

if (!function_exists('bk_env')) {
    function bk_env(string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, $_ENV)) {
            return (string) $_ENV[$key];
        }
        $fromGetenv = getenv($key);
        if ($fromGetenv !== false) {
            return (string) $fromGetenv;
        }
        return $default;
    }
}

bk_load_env($root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . '.env');

$business = require $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'business.php';

$whatsappDefault = (string) ($business['whatsapp'] ?? '5585986191000');

return [
    'env' => bk_env('APP_ENV', 'local'),
    'app_url' => rtrim((string) bk_env('APP_URL', ''), '/'),
    'business' => $business,
    'db' => [
        'host' => bk_env('DB_HOST', '127.0.0.1'),
        'port' => bk_env('DB_PORT', '3306'),
        'name' => bk_env('DB_NAME', 'bk_autos'),
        'user' => bk_env('DB_USER', 'root'),
        'pass' => bk_env('DB_PASS', ''),
        'charset' => 'utf8mb4',
    ],
    'rate_limit' => [
        'max' => (int) bk_env('RATE_LIMIT_MAX', '5'),
        'window' => (int) bk_env('RATE_LIMIT_WINDOW', '60'),
    ],
    'whatsapp' => preg_replace('/\D+/', '', (string) bk_env('WHATSAPP_NUMBER', $whatsappDefault)) ?: $whatsappDefault,
];
