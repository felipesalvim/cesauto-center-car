<?php

declare(strict_types=1);

/**
 * Endpoint público: recebe POST do formulário de leads.
 * Document root sugerido: /public
 */

$config = require dirname(__DIR__) . '/src/bootstrap.php';

$controller = new LeadController($config);
$controller->handlePost();
