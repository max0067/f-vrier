<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AIContentGenerator;
use App\Services\SemanticAnalyzer;
use App\Services\SerpScraper;

/**
 * Contrôleur de génération de contenu
 */
class ContentController extends Controller
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
     * Page principale de génération de contenu
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Générateur de Contenu IA';
        $this->data['currentPage'] = 'content';

        $this->view('content/index', [
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Génère un contenu complet
     */
    public function generate(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/content');
        }

        $prompt = $this->input('prompt', '');
        $type = $this->input('type', 'article');
        $tone = $this->input('tone', 'expert');
        $wordCount = (int)$this->input('word_count', 1500);
        $versions = (int)$this->input('versions', 1);
        $includeSerpAnalysis = (bool)$this->input('include_serp', true);
        $includeSemanticAnalysis = (bool)$this->input('include_semantic', true);

        if (empty($prompt)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Le prompt est requis'], 400);
            }
            $this->flash('error', 'Le prompt est requis');
            $this->redirect('/content');
        }

        try {
            $result = [];

            // Analyse SERP si demandée
            if ($includeSerpAnalysis) {
                $result['serp'] = $this->serpScraper->analyze($prompt);
            }

            // Analyse sémantique si demandée
            if ($includeSemanticAnalysis) {
                $result['semantic'] = $this->semanticAnalyzer->analyze($prompt);
            }

            // Génération du contenu
            $result['content'] = $this->generator->generate([
                'prompt' => $prompt,
                'type' => $type,
                'tone' => $tone,
                'word_count' => $wordCount,
                'versions' => $versions,
                'serp_data' => $result['serp'] ?? null,
                'semantic_data' => $result['semantic'] ?? null,
            ]);

            // Mise à jour des statistiques
            $this->updateStats($result['content']);

            // Sauvegarde dans l'historique
            $this->saveToHistory($prompt, $result);

            if ($this->isAjax()) {
                $this->json([
                    'success' => true,
                    'data' => $result,
                ]);
            }

            $_SESSION['generated_content'] = $result;
            $this->flash('success', 'Contenu généré avec succès');
            $this->redirect('/content');

        } catch (\Exception $e) {
            log_error('Content generation failed', ['error' => $e->getMessage()]);

            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur lors de la génération : ' . $e->getMessage());
            $this->redirect('/content');
        }
    }

    /**
     * Analyse un contenu existant
     */
    public function analyze(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $content = $this->input('content', '');
        $url = $this->input('url', '');

        if (empty($content) && empty($url)) {
            $this->json(['error' => 'Contenu ou URL requis'], 400);
        }

        try {
            $analysis = $this->semanticAnalyzer->analyzeContent($content ?: $url);

            $this->json([
                'success' => true,
                'data' => $analysis,
            ]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Historique des contenus générés
     */
    public function history(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Historique des contenus';
        $this->data['currentPage'] = 'content';

        $history = $_SESSION['content_history'] ?? [];

        $this->view('content/history', [
            'history' => $history,
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Affiche un contenu spécifique
     */
    public function show(string $id): void
    {
        $history = $_SESSION['content_history'] ?? [];
        $content = null;

        foreach ($history as $item) {
            if ($item['id'] === $id) {
                $content = $item;
                break;
            }
        }

        if (!$content) {
            $this->flash('error', 'Contenu non trouvé');
            $this->redirect('/content/history');
        }

        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Détail du contenu';
        $this->data['currentPage'] = 'content';

        $this->view('content/show', [
            'content' => $content,
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Supprime un contenu
     */
    public function delete(string $id): void
    {
        $history = $_SESSION['content_history'] ?? [];

        foreach ($history as $key => $item) {
            if ($item['id'] === $id) {
                unset($history[$key]);
                $_SESSION['content_history'] = array_values($history);
                break;
            }
        }

        if ($this->isAjax()) {
            $this->json(['success' => true]);
        }

        $this->flash('success', 'Contenu supprimé');
        $this->redirect('/content/history');
    }

    private function updateStats(array $content): void
    {
        if (!isset($_SESSION['stats'])) {
            $_SESSION['stats'] = ['contents' => 0, 'words' => 0];
        }

        $_SESSION['stats']['contents']++;
        $_SESSION['stats']['words'] += word_count($content['versions'][0]['content'] ?? '');
    }

    private function saveToHistory(string $prompt, array $result): void
    {
        if (!isset($_SESSION['content_history'])) {
            $_SESSION['content_history'] = [];
        }

        array_unshift($_SESSION['content_history'], [
            'id' => uniqid('content_'),
            'prompt' => $prompt,
            'result' => $result,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Limite à 50 éléments
        $_SESSION['content_history'] = array_slice($_SESSION['content_history'], 0, 50);
    }
}
