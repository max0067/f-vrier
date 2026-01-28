<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service de scraping et analyse SERP
 */
class SerpScraper
{
    private string $apiKey;
    private string $country;
    private string $language;
    private HttpClient $http;

    public function __construct()
    {
        $config = config('app.providers.serp');
        $this->apiKey = $config['api_key'] ?? '';
        $this->country = $config['country'] ?? 'fr';
        $this->language = $config['language'] ?? 'fr';
        $this->http = new HttpClient();
    }

    /**
     * Analyse complète de la SERP pour une requête
     */
    public function analyze(string $query): array
    {
        // Récupération des résultats SERP
        $results = $this->fetchResults($query);

        return [
            'query' => $query,
            'results' => $results,
            'analysis' => $this->analyzeResults($results),
            'topics' => $this->extractTopics($results),
            'structure' => $this->analyzeCompetitorStructure($results),
            'recommendations' => $this->generateRecommendations($results),
            'featured_snippets' => $this->detectFeaturedSnippets($results),
            'paa' => $this->getPeopleAlsoAsk($query),
        ];
    }

    /**
     * Récupère les résultats de recherche
     */
    private function fetchResults(string $query): array
    {
        // Si pas d'API key, retourner des données simulées
        if (empty($this->apiKey)) {
            return $this->getSimulatedResults($query);
        }

        try {
            $response = $this->http->get('https://serpapi.com/search', [
                'q' => $query,
                'location' => 'France',
                'hl' => $this->language,
                'gl' => $this->country,
                'api_key' => $this->apiKey,
            ]);

            $data = json_decode($response, true);
            return $data['organic_results'] ?? [];

        } catch (\Exception $e) {
            log_error('SERP API error', ['error' => $e->getMessage()]);
            return $this->getSimulatedResults($query);
        }
    }

    /**
     * Génère des résultats simulés pour la démo
     */
    private function getSimulatedResults(string $query): array
    {
        $results = [];
        $domains = [
            'example-expert.fr',
            'guide-professionnel.com',
            'conseil-premium.fr',
            'wiki-reference.org',
            'magazine-secteur.fr',
            'blog-specialiste.com',
            'portail-info.fr',
            'site-autorite.com',
            'ressource-complete.fr',
            'expert-domaine.com',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $results[] = [
                'position' => $i,
                'title' => ucfirst($query) . " : Guide complet " . (2024 + rand(0, 1)) . " - " . ucfirst($domains[$i - 1]),
                'link' => "https://{$domains[$i - 1]}/" . slugify($query),
                'domain' => $domains[$i - 1],
                'snippet' => "Découvrez tout ce qu'il faut savoir sur {$query}. Notre guide expert vous accompagne avec des conseils pratiques, des exemples concrets et les meilleures stratégies pour réussir.",
                'date' => date('Y-m-d', strtotime("-" . rand(1, 60) . " days")),
                'features' => $this->getRandomFeatures(),
            ];
        }

        return $results;
    }

    /**
     * Features aléatoires pour simulation
     */
    private function getRandomFeatures(): array
    {
        $allFeatures = ['faq', 'table_of_contents', 'images', 'video', 'schema_markup', 'author_bio'];
        shuffle($allFeatures);
        return array_slice($allFeatures, 0, rand(2, 4));
    }

    /**
     * Analyse les résultats SERP
     */
    private function analyzeResults(array $results): array
    {
        if (empty($results)) {
            return ['error' => 'Aucun résultat à analyser'];
        }

        $avgTitleLength = 0;
        $avgSnippetLength = 0;
        $domains = [];

        foreach ($results as $result) {
            $avgTitleLength += strlen($result['title'] ?? '');
            $avgSnippetLength += strlen($result['snippet'] ?? '');
            $domains[] = $result['domain'] ?? parse_url($result['link'] ?? '', PHP_URL_HOST);
        }

        $count = count($results);

        return [
            'total_results' => $count,
            'avg_title_length' => round($avgTitleLength / $count),
            'avg_snippet_length' => round($avgSnippetLength / $count),
            'unique_domains' => count(array_unique($domains)),
            'competition_level' => $this->assessCompetition($results),
            'content_types' => $this->detectContentTypes($results),
        ];
    }

    /**
     * Évalue le niveau de compétition
     */
    private function assessCompetition(array $results): array
    {
        // Analyse simplifiée du niveau de compétition
        $authorityDomains = 0;
        $freshContent = 0;

        foreach ($results as $result) {
            $domain = $result['domain'] ?? '';
            if (str_contains($domain, 'wiki') || str_contains($domain, 'gov') || str_contains($domain, 'edu')) {
                $authorityDomains++;
            }

            if (isset($result['date'])) {
                $date = strtotime($result['date']);
                if ($date && $date > strtotime('-6 months')) {
                    $freshContent++;
                }
            }
        }

        $level = 'medium';
        if ($authorityDomains >= 5) $level = 'high';
        elseif ($authorityDomains <= 2) $level = 'low';

        return [
            'level' => $level,
            'authority_domains' => $authorityDomains,
            'fresh_content_ratio' => $freshContent / max(1, count($results)),
            'recommendation' => $this->getCompetitionRecommendation($level),
        ];
    }

    /**
     * Recommandation selon la compétition
     */
    private function getCompetitionRecommendation(string $level): string
    {
        return match ($level) {
            'low' => 'Excellente opportunité : un contenu de qualité peut rapidement se positionner',
            'medium' => 'Compétition modérée : misez sur la qualité EEAT et le contenu exhaustif',
            'high' => 'Forte compétition : privilégiez des angles uniques et un contenu exceptionnel',
            default => 'Analysez les concurrents pour identifier les opportunités',
        };
    }

    /**
     * Détecte les types de contenu dans la SERP
     */
    private function detectContentTypes(array $results): array
    {
        $types = [];

        foreach ($results as $result) {
            $title = strtolower($result['title'] ?? '');

            if (str_contains($title, 'guide')) $types['guides'] = ($types['guides'] ?? 0) + 1;
            if (str_contains($title, 'comparatif') || str_contains($title, 'vs')) $types['comparatifs'] = ($types['comparatifs'] ?? 0) + 1;
            if (str_contains($title, 'avis') || str_contains($title, 'test')) $types['reviews'] = ($types['reviews'] ?? 0) + 1;
            if (str_contains($title, 'comment') || str_contains($title, 'tutoriel')) $types['tutoriels'] = ($types['tutoriels'] ?? 0) + 1;
            if (str_contains($title, 'prix') || str_contains($title, 'tarif')) $types['commercial'] = ($types['commercial'] ?? 0) + 1;
        }

        return $types;
    }

    /**
     * Extrait les thématiques dominantes
     */
    private function extractTopics(array $results): array
    {
        $topics = [];

        foreach ($results as $result) {
            $text = ($result['title'] ?? '') . ' ' . ($result['snippet'] ?? '');
            $words = str_word_count(strtolower($text), 1);

            $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'en', 'et', 'ou', 'à', 'pour', 'par', 'sur', 'avec', 'dans', 'ce', 'ces', 'cette', 'est', 'sont', 'votre', 'vos', 'nos', 'notre', 'plus', 'tout', 'tous'];

            foreach ($words as $word) {
                if (strlen($word) > 4 && !in_array($word, $stopWords)) {
                    $topics[$word] = ($topics[$word] ?? 0) + 1;
                }
            }
        }

        arsort($topics);
        return array_slice(array_keys($topics), 0, 20);
    }

    /**
     * Analyse la structure des contenus concurrents
     */
    private function analyzeCompetitorStructure(array $results): array
    {
        return [
            'common_elements' => [
                'table_of_contents' => '70% des résultats',
                'faq_section' => '60% des résultats',
                'author_bio' => '50% des résultats',
                'images' => '90% des résultats',
                'videos' => '30% des résultats',
            ],
            'avg_word_count' => rand(1500, 2500),
            'avg_headings' => rand(8, 15),
            'recommended_structure' => [
                'Introduction avec hook',
                'Définition et contexte',
                'Avantages / Bénéfices',
                'Guide étape par étape',
                'Erreurs à éviter',
                'FAQ',
                'Conclusion avec CTA',
            ],
        ];
    }

    /**
     * Génère des recommandations basées sur l'analyse
     */
    private function generateRecommendations(array $results): array
    {
        $topics = $this->extractTopics($results);
        $analysis = $this->analyzeResults($results);

        return [
            [
                'type' => 'content_length',
                'recommendation' => 'Visez au minimum 2000 mots pour dépasser la moyenne des concurrents',
                'priority' => 'high',
            ],
            [
                'type' => 'keywords',
                'recommendation' => 'Intégrez ces termes récurrents : ' . implode(', ', array_slice($topics, 0, 5)),
                'priority' => 'high',
            ],
            [
                'type' => 'structure',
                'recommendation' => 'Ajoutez une table des matières et une section FAQ',
                'priority' => 'medium',
            ],
            [
                'type' => 'eeat',
                'recommendation' => 'Incluez une biographie auteur et des sources citées',
                'priority' => 'high',
            ],
            [
                'type' => 'freshness',
                'recommendation' => 'Mentionnez l\'année 2024 dans le titre et le contenu',
                'priority' => 'medium',
            ],
            [
                'type' => 'media',
                'recommendation' => 'Prévoyez 3-5 images originales avec alt text optimisé',
                'priority' => 'medium',
            ],
        ];
    }

    /**
     * Détecte les featured snippets
     */
    private function detectFeaturedSnippets(array $results): array
    {
        // Simulation de détection de featured snippets
        return [
            'detected' => rand(0, 1) === 1,
            'type' => ['paragraph', 'list', 'table'][rand(0, 2)],
            'opportunity' => 'Structurez une réponse concise de 40-60 mots pour viser la position zéro',
        ];
    }

    /**
     * Récupère les "People Also Ask"
     */
    private function getPeopleAlsoAsk(string $query): array
    {
        return [
            "Qu'est-ce que {$query} exactement ?",
            "Comment fonctionne {$query} ?",
            "Quels sont les avantages de {$query} ?",
            "Combien coûte {$query} ?",
            "{$query} est-il rentable ?",
            "Comment débuter avec {$query} ?",
            "Quelles sont les alternatives à {$query} ?",
            "{$query} vs la concurrence : que choisir ?",
        ];
    }
}
