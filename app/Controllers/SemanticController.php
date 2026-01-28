<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SemanticAnalyzer;

/**
 * Contrôleur d'analyse sémantique
 */
class SemanticController extends Controller
{
    private SemanticAnalyzer $analyzer;

    public function __construct()
    {
        $this->analyzer = new SemanticAnalyzer();
    }

    /**
     * Page d'analyse sémantique
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Analyse Sémantique';
        $this->data['currentPage'] = 'semantic';

        $this->view('semantic/index', [
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Effectue l'analyse sémantique
     */
    public function analyze(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/semantic');
        }

        $query = $this->input('query', '');
        $content = $this->input('content', '');

        if (empty($query) && empty($content)) {
            $this->flash('error', 'Veuillez saisir une requête ou un contenu');
            $this->redirect('/semantic');
        }

        try {
            if (!empty($content)) {
                $result = $this->analyzer->analyzeContent($content);
                $result['type'] = 'content';
            } else {
                $result = $this->analyzer->analyze($query);
                $result['type'] = 'query';
            }

            if ($this->isAjax()) {
                $this->json(['success' => true, 'data' => $result]);
            }

            $_SESSION['semantic_result'] = $result;
            $this->flash('success', 'Analyse sémantique terminée');
            $this->redirect('/semantic');

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/semantic');
        }
    }
}
