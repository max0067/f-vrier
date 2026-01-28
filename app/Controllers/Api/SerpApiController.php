<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Services\SerpScraper;

/**
 * API Controller pour l'analyse SERP
 */
class SerpApiController extends Controller
{
    private SerpScraper $scraper;

    public function __construct()
    {
        $this->scraper = new SerpScraper();
    }

    /**
     * Analyse la SERP pour une requête
     */
    public function scrape(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $query = $this->input('query', '');

        if (empty($query)) {
            $this->json(['error' => 'La requête est requise'], 400);
        }

        try {
            $result = $this->scraper->analyze($query);

            // Mise à jour stats
            if (!isset($_SESSION['stats']['serp'])) {
                $_SESSION['stats']['serp'] = 0;
            }
            $_SESSION['stats']['serp']++;

            $this->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            log_error('SERP analysis failed', ['error' => $e->getMessage()]);
            $this->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
