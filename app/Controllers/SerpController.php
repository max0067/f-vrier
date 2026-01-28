<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SerpScraper;

/**
 * Contrôleur d'analyse SERP
 */
class SerpController extends Controller
{
    private SerpScraper $scraper;

    public function __construct()
    {
        $this->scraper = new SerpScraper();
    }

    /**
     * Page d'analyse SERP
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Analyse SERP';
        $this->data['currentPage'] = 'serp';

        $this->view('serp/index', [
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Effectue l'analyse SERP
     */
    public function analyze(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/serp');
        }

        $query = $this->input('query', '');

        if (empty($query)) {
            $this->flash('error', 'Veuillez saisir une requête');
            $this->redirect('/serp');
        }

        try {
            $result = $this->scraper->analyze($query);

            // Mise à jour stats
            if (!isset($_SESSION['stats']['serp'])) {
                $_SESSION['stats']['serp'] = 0;
            }
            $_SESSION['stats']['serp']++;

            // Sauvegarde dans historique
            $resultId = uniqid('serp_');
            $_SESSION['serp_results'][$resultId] = [
                'id' => $resultId,
                'query' => $query,
                'result' => $result,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->isAjax()) {
                $this->json(['success' => true, 'data' => $result, 'id' => $resultId]);
            }

            $this->flash('success', 'Analyse SERP terminée');
            $this->redirect('/serp/results/' . $resultId);

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/serp');
        }
    }

    /**
     * Affiche les résultats d'une analyse
     */
    public function results(string $id): void
    {
        $result = $_SESSION['serp_results'][$id] ?? null;

        if (!$result) {
            $this->flash('error', 'Analyse non trouvée');
            $this->redirect('/serp');
        }

        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Résultats SERP - ' . $result['query'];
        $this->data['currentPage'] = 'serp';

        $this->view('serp/results', [
            'result' => $result,
            'flash' => $this->getFlash(),
        ]);
    }
}
