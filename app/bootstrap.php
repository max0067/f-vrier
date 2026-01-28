<?php

declare(strict_types=1);

/**
 * Bootstrap de l'application
 * Chargement des dépendances et configuration initiale
 */

// Autoloader simple si Composer n'est pas disponible
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Chargement des helpers
require_once APP_PATH . '/Helpers/functions.php';
