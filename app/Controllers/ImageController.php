<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ImageGenerator;

/**
 * Contrôleur de génération d'images
 */
class ImageController extends Controller
{
    private ImageGenerator $generator;

    public function __construct()
    {
        $this->generator = new ImageGenerator();
    }

    /**
     * Page de génération d'images
     */
    public function index(): void
    {
        $this->data['layout'] = 'main';
        $this->data['pageTitle'] = 'Générateur d\'Images IA';
        $this->data['currentPage'] = 'images';

        $this->view('images/index', [
            'flash' => $this->getFlash(),
            'generatedImage' => $_SESSION['generated_image'] ?? null,
        ]);

        unset($_SESSION['generated_image']);
    }

    /**
     * Génère une image
     */
    public function generate(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/images');
        }

        $subject = $this->input('subject', '');
        $style = $this->input('style', 'photorealistic');
        $size = $this->input('size', '1792x1024');

        if (empty($subject)) {
            $this->flash('error', 'Veuillez décrire l\'image souhaitée');
            $this->redirect('/images');
        }

        try {
            $result = $this->generator->generate([
                'subject' => $subject,
                'style' => $style,
                'size' => $size,
            ]);

            // Mise à jour stats
            if (!isset($_SESSION['stats']['images'])) {
                $_SESSION['stats']['images'] = 0;
            }
            $_SESSION['stats']['images']++;

            if ($this->isAjax()) {
                $this->json(['success' => true, 'data' => $result]);
            }

            $_SESSION['generated_image'] = $result;
            $this->flash('success', 'Image générée avec succès');
            $this->redirect('/images');

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }

            $this->flash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/images');
        }
    }
}
