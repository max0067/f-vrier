<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Contrôleur du tableau de bord
 */
class DashboardController extends Controller
{
    /**
     * Page d'accueil / Dashboard
     */
    public function index(): void
    {
        $stats = [
            'contents_generated' => $this->getContentCount(),
            'serp_analyses' => $this->getSerpAnalysesCount(),
            'images_created' => $this->getImagesCount(),
            'words_written' => $this->getTotalWords(),
        ];

        $recentContents = $this->getRecentContents(5);
        $recentAnalyses = $this->getRecentAnalyses(5);

        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Tableau de bord';
        $this->data['currentPage'] = 'dashboard';

        $this->view('dashboard/index', [
            'stats' => $stats,
            'recentContents' => $recentContents,
            'recentAnalyses' => $recentAnalyses,
            'flash' => $this->getFlash(),
        ]);
    }

    private function getContentCount(): int
    {
        // Simulation - à remplacer par données réelles
        return $_SESSION['stats']['contents'] ?? 0;
    }

    private function getSerpAnalysesCount(): int
    {
        return $_SESSION['stats']['serp'] ?? 0;
    }

    private function getImagesCount(): int
    {
        return $_SESSION['stats']['images'] ?? 0;
    }

    private function getTotalWords(): int
    {
        return $_SESSION['stats']['words'] ?? 0;
    }

    private function getRecentContents(int $limit): array
    {
        return $_SESSION['recent_contents'] ?? [];
    }

    private function getRecentAnalyses(int $limit): array
    {
        return $_SESSION['recent_analyses'] ?? [];
    }
}
