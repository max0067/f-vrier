<?php

declare(strict_types=1);

/**
 * SEO Content Generator - Point d'entrée principal
 * Application SaaS de génération de contenus IA optimisés SEO
 */

// Vérification de la version PHP
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    die('PHP 8.1+ requis. Version actuelle : ' . PHP_VERSION);
}

// Définition des constantes
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', __DIR__);

// Chargement de l'autoloader Composer
$autoloader = ROOT_PATH . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require $autoloader;
}

// Chargement des variables d'environnement
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
}

// Configuration des erreurs
$debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Timezone
date_default_timezone_set('Europe/Paris');

// Démarrage de la session
session_start();

// Chargement de l'application
require_once APP_PATH . '/bootstrap.php';

// Initialisation et exécution de l'application
$app = new App\Application();
$app->run();
