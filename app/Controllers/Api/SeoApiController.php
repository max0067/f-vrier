<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Services\SeoOptimizer;

/**
 * API Controller pour l'optimisation SEO
 */
class SeoApiController extends Controller
{
    private SeoOptimizer $optimizer;

    public function __construct()
    {
        $this->optimizer = new SeoOptimizer();
    }

    /**
     * Optimise un contenu pour le SEO
     */
    public function optimize(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $content = $this->input('content', '');
        $keyword = $this->input('keyword', '');

        if (empty($content) || empty($keyword)) {
            $this->json(['error' => 'Le contenu et le mot-clé sont requis'], 400);
        }

        try {
            $result = $this->optimizer->optimize($content, $keyword);

            $this->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            log_error('SEO optimization failed', ['error' => $e->getMessage()]);
            $this->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Génère les meta tags
     */
    public function generateMeta(): void
    {
        $content = $this->input('content', '');
        $keyword = $this->input('keyword', '');

        if (empty($keyword)) {
            $this->json(['error' => 'Le mot-clé est requis'], 400);
        }

        $meta = $this->optimizer->generateMeta($content, $keyword);

        $this->json([
            'status' => 'success',
            'data' => $meta,
        ]);
    }

    /**
     * Suggère des liens internes
     */
    public function internalLinks(): void
    {
        $keyword = $this->input('keyword', '');

        if (empty($keyword)) {
            $this->json(['error' => 'Le mot-clé est requis'], 400);
        }

        $links = $this->optimizer->suggestInternalLinks($keyword);
        $anchors = $this->optimizer->generateAnchors($keyword);

        $this->json([
            'status' => 'success',
            'data' => [
                'internal_links' => $links,
                'anchors' => $anchors,
            ],
        ]);
    }
}
