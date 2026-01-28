<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Contrôleur des paramètres
 */
class SettingsController extends Controller
{
    /**
     * Page des paramètres
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Paramètres';
        $this->data['currentPage'] = 'settings';

        $settings = $_SESSION['user_settings'] ?? $this->getDefaultSettings();

        $this->view('settings/index', [
            'settings' => $settings,
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Met à jour les paramètres
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/settings');
        }

        $settings = [
            'company_name' => sanitize($this->input('company_name', '')),
            'default_tone' => $this->input('default_tone', 'expert'),
            'default_word_count' => (int)$this->input('default_word_count', 1500),
            'include_serp_by_default' => (bool)$this->input('include_serp_by_default', true),
            'include_semantic_by_default' => (bool)$this->input('include_semantic_by_default', true),
            'api_provider' => $this->input('api_provider', 'openai'),
            'language' => $this->input('language', 'fr'),
        ];

        $_SESSION['user_settings'] = $settings;

        $this->flash('success', 'Paramètres enregistrés avec succès');
        $this->redirect('/settings');
    }

    /**
     * Paramètres par défaut
     */
    private function getDefaultSettings(): array
    {
        return [
            'company_name' => '',
            'default_tone' => 'expert',
            'default_word_count' => 1500,
            'include_serp_by_default' => true,
            'include_semantic_by_default' => true,
            'api_provider' => 'openai',
            'language' => 'fr',
        ];
    }
}
