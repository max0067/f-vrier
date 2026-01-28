<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service de génération d'images IA réalistes
 */
class ImageGenerator
{
    private string $apiKey;
    private string $model;
    private HttpClient $http;

    public function __construct()
    {
        $config = config('app.providers.image');
        $this->apiKey = $config['api_key'] ?? '';
        $this->model = $config['model'] ?? 'dall-e-3';
        $this->http = new HttpClient();
    }

    /**
     * Génère une image à partir d'un sujet
     */
    public function generate(array $options): array
    {
        $subject = $options['subject'] ?? '';
        $style = $options['style'] ?? 'photorealistic';
        $size = $options['size'] ?? '1792x1024';
        $quality = $options['quality'] ?? 'hd';

        // Génération du prompt optimisé
        $prompt = $this->buildImagePrompt($subject, $style);

        // Appel API ou simulation
        $result = $this->callAPI($prompt, $size, $quality);

        return [
            'prompt' => $prompt,
            'image_url' => $result['url'],
            'revised_prompt' => $result['revised_prompt'] ?? $prompt,
            'size' => $size,
            'style' => $style,
            'seo' => $this->generateImageSEO($subject),
        ];
    }

    /**
     * Construit un prompt optimisé pour des images réalistes
     */
    private function buildImagePrompt(string $subject, string $style): string
    {
        $styleModifiers = match ($style) {
            'photorealistic' => 'Ultra-realistic photograph, professional photography, high resolution, natural lighting, sharp focus, 8K quality',
            'editorial' => 'Editorial style photograph, magazine quality, professional studio lighting, clean composition',
            'lifestyle' => 'Lifestyle photography, authentic moment, warm natural light, candid feel, professional quality',
            'corporate' => 'Corporate professional photograph, business setting, clean modern aesthetic, sharp and polished',
            'artistic' => 'Artistic photograph with creative composition, unique perspective, professional quality',
            default => 'High quality professional photograph',
        };

        $prompt = "{$subject}. {$styleModifiers}. ";
        $prompt .= "No text, no watermarks, no logos. Photorealistic, detailed, professional composition.";

        return $prompt;
    }

    /**
     * Appelle l'API de génération d'images
     */
    private function callAPI(string $prompt, string $size, string $quality): array
    {
        if (empty($this->apiKey)) {
            return $this->getPlaceholderImage($prompt);
        }

        try {
            $response = $this->http->post('https://api.openai.com/v1/images/generations', [
                'model' => $this->model,
                'prompt' => $prompt,
                'n' => 1,
                'size' => $size,
                'quality' => $quality,
                'response_format' => 'url',
            ], [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);

            $data = json_decode($response, true);

            return [
                'url' => $data['data'][0]['url'] ?? '',
                'revised_prompt' => $data['data'][0]['revised_prompt'] ?? $prompt,
            ];

        } catch (\Exception $e) {
            log_error('Image generation error', ['error' => $e->getMessage()]);
            return $this->getPlaceholderImage($prompt);
        }
    }

    /**
     * Retourne une image placeholder pour la démo
     */
    private function getPlaceholderImage(string $prompt): array
    {
        // Utilisation d'un service de placeholder
        $encodedPrompt = urlencode(substr($prompt, 0, 50));

        return [
            'url' => "https://placehold.co/1792x1024/6366F1/ffffff?text=Image+IA",
            'revised_prompt' => $prompt,
            'is_placeholder' => true,
        ];
    }

    /**
     * Génère les attributs SEO pour l'image
     */
    private function generateImageSEO(string $subject): array
    {
        $slug = slugify($subject);

        return [
            'filename' => $slug . '-featured-image.jpg',
            'alt_text' => ucfirst($subject) . ' - Image illustrative',
            'title' => ucfirst($subject),
            'caption' => 'Illustration pour ' . $subject,
            'description' => 'Image à la une pour l\'article sur ' . $subject . '. Photo de qualité professionnelle.',
        ];
    }

    /**
     * Génère plusieurs variations de prompts d'images
     */
    public function generatePromptVariations(string $subject): array
    {
        return [
            [
                'style' => 'photorealistic',
                'prompt' => $this->buildImagePrompt($subject, 'photorealistic'),
                'description' => 'Photo ultra-réaliste, idéale pour image principale',
            ],
            [
                'style' => 'editorial',
                'prompt' => $this->buildImagePrompt($subject, 'editorial'),
                'description' => 'Style éditorial magazine, professionnel',
            ],
            [
                'style' => 'lifestyle',
                'prompt' => $this->buildImagePrompt($subject, 'lifestyle'),
                'description' => 'Style lifestyle authentique et chaleureux',
            ],
            [
                'style' => 'corporate',
                'prompt' => $this->buildImagePrompt($subject, 'corporate'),
                'description' => 'Style corporate pour contexte business',
            ],
        ];
    }
}
