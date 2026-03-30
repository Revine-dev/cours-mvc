<?php

declare(strict_types=1);

if (!defined('ROOT')) {
    define("ROOT", dirname(__DIR__));
}

if (!defined('DS')) {
    define("DS", DIRECTORY_SEPARATOR);
}

if (!defined("VIEWS")) {
    define("VIEWS", ROOT . DS . 'src' . DS . 'Application' . DS . 'Views' . DS);
}

if (!defined("LOCAL")) {
    define(
        "LOCAL",
        PHP_SAPI === 'cli' ||
            (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost')
    );
}

// Simple .env
$envPath = ROOT . DS . '.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        putenv(trim($line));
    }
}

// Enregistrement automatiquement de la configuration du fichier config.php dans le registre de configuration
\App\Application\Config\ConfigRegistry::register(require __DIR__ . DS . 'config.php');
