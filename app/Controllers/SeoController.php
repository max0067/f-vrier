<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SeoOptimizer;

/**
 * Contrôleur SEO technique
 */
class SeoController extends Controller
{
    private SeoOptimizer $optimizer;

    public function __construct()
    {
        $this->optimizer = new SeoOptimizer();
    }

    /**
     * Page SEO technique
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'SEO Technique';
        $this->data['currentPage'] = 'seo';

        $this->view('seo/index', [
            'flash' => $this->getFlash(),
            'seoResult' => $_SESSION['seo_result'] ?? null,
        ]);

        unset($_SESSION['seo_result']);
    }

    /**
     * Génère les meta tags
     */
    public function generateMeta(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/seo');
        }

        $content = $this->input('content', '');
        $keyword = $this->input('keyword', '');

        if (empty($keyword)) {
            $this->flash('error', 'Le mot-clé principal est requis');
            $this->redirect('/seo');
        }

        try {
            $result = $this->optimizer->optimize($content, $keyword);

            if ($this->isAjax()) {
                $this->json(['success' => true, 'data' => $result]);
            }

            $_SESSION['seo_result'] = $result;
            $this->flash('success', 'Optimisation SEO générée');
            $this->redirect('/seo');

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/seo');
        }
    }

    /**
     * Génère les suggestions de maillage interne
     */
    public function internalLinks(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/seo');
        }

        $keyword = $this->input('keyword', '');

        if (empty($keyword)) {
            $this->flash('error', 'Le mot-clé principal est requis');
            $this->redirect('/seo');
        }

        try {
            $links = $this->optimizer->suggestInternalLinks($keyword);
            $anchors = $this->optimizer->generateAnchors($keyword);

            $result = [
                'internal_links' => $links,
                'anchors' => $anchors,
            ];

            if ($this->isAjax()) {
                $this->json(['success' => true, 'data' => $result]);
            }

            $_SESSION['seo_result'] = $result;
            $this->redirect('/seo');

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/seo');
        }
    }
}
