<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Contrôleur de base
 */
abstract class Controller
{
    protected array $data = [];

    /**
     * Rendu d'une vue
     */
    protected function view(string $view, array $data = []): void
    {
        $this->data = array_merge($this->data, $data);
        extract($this->data);

        $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        // Capture du contenu
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Rendu avec layout si défini
        if (isset($this->data['layout'])) {
            $layoutPath = APP_PATH . '/Views/layouts/' . $this->data['layout'] . '.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
                return;
            }
        }

        echo $content;
    }

    /**
     * Réponse JSON
     */
    protected function json(mixed $data, int $status = 200): void
    {
        json_response($data, $status);
    }

    /**
     * Récupère les données de la requête
     */
    protected function input(string $key = null, mixed $default = null): mixed
    {
        $data = array_merge($_GET, $_POST);

        // Gestion des requêtes JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $jsonData = json_decode(file_get_contents('php://input'), true) ?? [];
            $data = array_merge($data, $jsonData);
        }

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }

    /**
     * Vérifie si la requête est POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Vérifie si c'est une requête AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Valide le token CSRF
     */
    protected function validateCsrf(): bool
    {
        $token = $this->input('_token');
        return verify_csrf($token);
    }

    /**
     * Définit un message flash
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * Récupère et supprime le message flash
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Redirige vers une URL
     */
    protected function redirect(string $url): never
    {
        redirect($url);
    }
}
