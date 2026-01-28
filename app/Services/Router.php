<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Système de routage de l'application
 */
class Router
{
    private array $routes = [];
    private array $params = [];

    /**
     * Ajoute une route GET
     */
    public function get(string $path, string $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Ajoute une route POST
     */
    public function post(string $path, string $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Ajoute une route PUT
     */
    public function put(string $path, string $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Ajoute une route DELETE
     */
    public function delete(string $path, string $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Ajoute une route
     */
    private function addRoute(string $method, string $path, string $handler): self
    {
        $pattern = $this->convertToRegex($path);
        $this->routes[$method][$pattern] = [
            'handler' => $handler,
            'path' => $path,
        ];
        return $this;
    }

    /**
     * Convertit un chemin en expression régulière
     */
    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Dispatche la requête vers le bon contrôleur
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        // Support des méthodes PUT/DELETE via _method
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $pattern => $route) {
            if (preg_match($pattern, $uri, $matches)) {
                $this->params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($route['handler']);
                return;
            }
        }

        $this->notFound();
    }

    /**
     * Récupère l'URI de la requête
     */
    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        return $uri;
    }

    /**
     * Appelle le handler de la route
     */
    private function callHandler(string $handler): void
    {
        [$controllerName, $method] = explode('@', $handler);

        // Gestion des namespaces
        if (str_contains($controllerName, '\\')) {
            $controllerClass = 'App\\Controllers\\' . $controllerName;
        } else {
            $controllerClass = 'App\\Controllers\\' . $controllerName;
        }

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Method not found: {$controllerClass}@{$method}");
        }

        // Appel de la méthode avec les paramètres de route
        call_user_func_array([$controller, $method], $this->params);
    }

    /**
     * Gère les erreurs 404
     */
    private function notFound(): void
    {
        http_response_code(404);

        if ($this->isAjax()) {
            json_response(['error' => 'Route not found'], 404);
        }

        require_once APP_PATH . '/Views/errors/404.php';
    }

    /**
     * Vérifie si c'est une requête AJAX
     */
    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Récupère les paramètres de route
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
