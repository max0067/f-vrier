<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Services\AIContentGenerator;
use App\Services\SemanticAnalyzer;
use App\Services\SerpScraper;

/**
 * API Controller pour la génération de contenu
 */
class ContentApiController extends Controller
{
    private AIContentGenerator $generator;
    private SemanticAnalyzer $semanticAnalyzer;
    private SerpScraper $serpScraper;

    public function __construct()
    {
        $this->generator = new AIContentGenerator();
        $this->semanticAnalyzer = new SemanticAnalyzer();
        $this->serpScraper = new SerpScraper();
    }

    /**
     * Génère un contenu via API
     */
    public function generate(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $prompt = $this->input('prompt', '');
        $type = $this->input('type', 'article');
        $tone = $this->input('tone', 'expert');
        $wordCount = (int)$this->input('word_count', 1500);
        $versions = (int)$this->input('versions', 1);
        $includeSerpAnalysis = (bool)$this->input('include_serp', true);
        $includeSemanticAnalysis = (bool)$this->input('include_semantic', true);

        if (empty($prompt)) {
            $this->json(['error' => 'Le prompt est requis'], 400);
        }

        try {
            $result = [
                'status' => 'success',
                'data' => [],
            ];

            // Analyse SERP si demandée
            if ($includeSerpAnalysis) {
                $result['data']['serp'] = $this->serpScraper->analyze($prompt);
            }

            // Analyse sémantique si demandée
            if ($includeSemanticAnalysis) {
                $result['data']['semantic'] = $this->semanticAnalyzer->analyze($prompt);
            }

            // Génération du contenu
            $result['data']['content'] = $this->generator->generate([
                'prompt' => $prompt,
                'type' => $type,
                'tone' => $tone,
                'word_count' => $wordCount,
                'versions' => $versions,
                'serp_data' => $result['data']['serp'] ?? null,
                'semantic_data' => $result['data']['semantic'] ?? null,
            ]);

            // Mise à jour des statistiques session
            $this->updateSessionStats($result['data']['content']);

            $this->json($result);

        } catch (\Exception $e) {
            log_error('API content generation failed', ['error' => $e->getMessage()]);
            $this->json([
                'status' => 'error',
                'error' => 'Erreur lors de la génération : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Met à jour les statistiques de session
     */
    private function updateSessionStats(array $content): void
    {
        if (!isset($_SESSION['stats'])) {
            $_SESSION['stats'] = ['contents' => 0, 'words' => 0];
        }

        $_SESSION['stats']['contents']++;

        if (isset($content['versions'][0]['word_count'])) {
            $_SESSION['stats']['words'] += $content['versions'][0]['word_count'];
        }
    }
}
