<?php

declare(strict_types=1);

/**
 * Bootstrap da aplicação (fora de /public).
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$config = require dirname(__DIR__) . '/config/config.php';

spl_autoload_register(static function (string $class): void {
    $map = [
        'Database' => 'models/Database.php',
        'Lead' => 'models/Lead.php',
        'RateLimit' => 'models/RateLimit.php',
        'Csrf' => 'helpers/Csrf.php',
        'LeadController' => 'controllers/LeadController.php',
    ];

    if (!isset($map[$class])) {
        return;
    }

    $file = __DIR__ . '/' . $map[$class];
    if (is_readable($file)) {
        require_once $file;
    }
});

return $config;
