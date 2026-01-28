<?php

declare(strict_types=1);

namespace App;

use App\Services\Router;

/**
 * Classe principale de l'application
 */
class Application
{
    private Router $router;
    private array $config;

    public function __construct()
    {
        $this->config = require CONFIG_PATH . '/app.php';
        $this->router = new Router();
        $this->registerRoutes();
    }

    /**
     * Enregistre toutes les routes de l'application
     */
    private function registerRoutes(): void
    {
        // Routes principales
        $this->router->get('/', 'DashboardController@index');
        $this->router->get('/dashboard', 'DashboardController@index');

        // Routes de génération de contenu
        $this->router->get('/content', 'ContentController@index');
        $this->router->post('/content/generate', 'ContentController@generate');
        $this->router->post('/content/analyze', 'ContentController@analyze');
        $this->router->get('/content/history', 'ContentController@history');
        $this->router->get('/content/{id}', 'ContentController@show');
        $this->router->delete('/content/{id}', 'ContentController@delete');

        // Routes d'analyse SERP
        $this->router->get('/serp', 'SerpController@index');
        $this->router->post('/serp/analyze', 'SerpController@analyze');
        $this->router->get('/serp/results/{id}', 'SerpController@results');

        // Routes d'analyse sémantique
        $this->router->get('/semantic', 'SemanticController@index');
        $this->router->post('/semantic/analyze', 'SemanticController@analyze');

        // Routes de génération d'images
        $this->router->get('/images', 'ImageController@index');
        $this->router->post('/images/generate', 'ImageController@generate');

        // Routes SEO technique
        $this->router->get('/seo', 'SeoController@index');
        $this->router->post('/seo/meta', 'SeoController@generateMeta');
        $this->router->post('/seo/internal-links', 'SeoController@internalLinks');

        // Routes API (AJAX)
        $this->router->post('/api/content/generate', 'Api\ContentApiController@generate');
        $this->router->post('/api/serp/scrape', 'Api\SerpApiController@scrape');
        $this->router->post('/api/semantic/analyze', 'Api\SemanticApiController@analyze');
        $this->router->post('/api/image/generate', 'Api\ImageApiController@generate');
        $this->router->post('/api/seo/optimize', 'Api\SeoApiController@optimize');

        // Routes d'export
        $this->router->post('/export/wordpress', 'ExportController@wordpress');
        $this->router->post('/export/html', 'ExportController@html');
        $this->router->post('/export/markdown', 'ExportController@markdown');

        // Routes utilitaires
        $this->router->get('/settings', 'SettingsController@index');
        $this->router->post('/settings', 'SettingsController@update');
    }

    /**
     * Exécute l'application
     */
    public function run(): void
    {
        try {
            $this->router->dispatch();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Gère les exceptions
     */
    private function handleException(\Throwable $e): void
    {
        log_error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        if ($this->config['debug']) {
            http_response_code(500);
            echo '<h1>Erreur Application</h1>';
            echo '<p><strong>Message:</strong> ' . e($e->getMessage()) . '</p>';
            echo '<p><strong>Fichier:</strong> ' . e($e->getFile()) . ':' . $e->getLine() . '</p>';
            echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
        } else {
            http_response_code(500);
            require_once APP_PATH . '/Views/errors/500.php';
        }
    }
}
