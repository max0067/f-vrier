<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Services\SemanticAnalyzer;

/**
 * API Controller pour l'analyse sémantique
 */
class SemanticApiController extends Controller
{
    private SemanticAnalyzer $analyzer;

    public function __construct()
    {
        $this->analyzer = new SemanticAnalyzer();
    }

    /**
     * Analyse sémantique d'un sujet ou contenu
     */
    public function analyze(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $query = $this->input('query', '');
        $content = $this->input('content', '');

        if (empty($query) && empty($content)) {
            $this->json(['error' => 'Une requête ou un contenu est requis'], 400);
        }

        try {
            if (!empty($content)) {
                $result = $this->analyzer->analyzeContent($content);
            } else {
                $result = $this->analyzer->analyze($query);
            }

            $this->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            log_error('Semantic analysis failed', ['error' => $e->getMessage()]);
            $this->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
