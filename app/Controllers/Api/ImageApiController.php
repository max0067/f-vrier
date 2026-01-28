<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Services\ImageGenerator;

/**
 * API Controller pour la génération d'images
 */
class ImageApiController extends Controller
{
    private ImageGenerator $generator;

    public function __construct()
    {
        $this->generator = new ImageGenerator();
    }

    /**
     * Génère une image
     */
    public function generate(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $subject = $this->input('subject', '');
        $style = $this->input('style', 'photorealistic');
        $size = $this->input('size', '1792x1024');

        if (empty($subject)) {
            $this->json(['error' => 'Le sujet est requis'], 400);
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

            $this->json([
                'status' => 'success',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            log_error('Image generation failed', ['error' => $e->getMessage()]);
            $this->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Génère des variations de prompts
     */
    public function variations(): void
    {
        $subject = $this->input('subject', '');

        if (empty($subject)) {
            $this->json(['error' => 'Le sujet est requis'], 400);
        }

        $variations = $this->generator->generatePromptVariations($subject);

        $this->json([
            'status' => 'success',
            'data' => $variations,
        ]);
    }
}
