<?php

declare(strict_types=1);

/**
 * Fonctions utilitaires globales
 */

if (!function_exists('config')) {
    /**
     * Récupère une valeur de configuration
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $config = [];

        $parts = explode('.', $key);
        $file = $parts[0];

        if (!isset($config[$file])) {
            $path = CONFIG_PATH . '/' . $file . '.php';
            if (file_exists($path)) {
                $config[$file] = require $path;
            } else {
                return $default;
            }
        }

        $value = $config[$file];
        array_shift($parts);

        foreach ($parts as $part) {
            if (!is_array($value) || !isset($value[$part])) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }
}

if (!function_exists('env')) {
    /**
     * Récupère une variable d'environnement
     */
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false) {
            return $default;
        }

        return match (strtolower($value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            'empty', '(empty)' => '',
            default => $value,
        };
    }
}

if (!function_exists('e')) {
    /**
     * Échappe les caractères HTML
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('url')) {
    /**
     * Génère une URL complète
     */
    function url(string $path = ''): string
    {
        $baseUrl = rtrim(config('app.url', ''), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Génère une URL vers un asset
     */
    function asset(string $path): string
    {
        return url($path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirige vers une URL
     */
    function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('json_response')) {
    /**
     * Renvoie une réponse JSON
     */
    function json_response(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Génère ou récupère le token CSRF
     */
    function csrf_token(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Génère un champ hidden CSRF
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf')) {
    /**
     * Vérifie le token CSRF
     */
    function verify_csrf(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) &&
               hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}

if (!function_exists('sanitize')) {
    /**
     * Nettoie une chaîne pour l'affichage
     */
    function sanitize(string $value): string
    {
        return strip_tags(trim($value));
    }
}

if (!function_exists('slugify')) {
    /**
     * Génère un slug URL-friendly
     */
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text ?: 'n-a';
    }
}

if (!function_exists('format_bytes')) {
    /**
     * Formate une taille en bytes
     */
    function format_bytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('time_ago')) {
    /**
     * Convertit un timestamp en format relatif
     */
    function time_ago(int|string $timestamp): string
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'À l\'instant';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return 'Il y a ' . $mins . ' min' . ($mins > 1 ? 's' : '');
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return 'Il y a ' . $hours . ' h';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return 'Il y a ' . $days . ' jour' . ($days > 1 ? 's' : '');
        } else {
            return date('d/m/Y', $timestamp);
        }
    }
}

if (!function_exists('truncate')) {
    /**
     * Tronque un texte avec des points de suspension
     */
    function truncate(string $text, int $length = 150, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('word_count')) {
    /**
     * Compte les mots d'un texte
     */
    function word_count(string $text): int
    {
        return str_word_count(strip_tags($text));
    }
}

if (!function_exists('reading_time')) {
    /**
     * Estime le temps de lecture
     */
    function reading_time(string $text, int $wordsPerMinute = 200): int
    {
        $words = word_count($text);
        return (int) ceil($words / $wordsPerMinute);
    }
}

if (!function_exists('log_error')) {
    /**
     * Log une erreur
     */
    function log_error(string $message, array $context = []): void
    {
        $logPath = STORAGE_PATH . '/logs/error.log';
        $logDir = dirname($logPath);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $entry = sprintf(
            "[%s] %s %s\n",
            date('Y-m-d H:i:s'),
            $message,
            !empty($context) ? json_encode($context) : ''
        );

        file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX);
    }
}
