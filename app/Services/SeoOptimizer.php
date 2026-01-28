<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service d'optimisation SEO technique
 */
class SeoOptimizer
{
    /**
     * Génère tous les éléments SEO pour un contenu
     */
    public function optimize(string $content, string $keyword): array
    {
        return [
            'meta' => $this->generateMeta($content, $keyword),
            'schema' => $this->generateSchema($content, $keyword),
            'internal_links' => $this->suggestInternalLinks($keyword),
            'anchors' => $this->generateAnchors($keyword),
            'readability' => $this->analyzeReadability($content),
            'keyword_analysis' => $this->analyzeKeywords($content, $keyword),
            'checklist' => $this->getSEOChecklist($content, $keyword),
        ];
    }

    /**
     * Génère les meta tags optimisés
     */
    public function generateMeta(string $content, string $keyword): array
    {
        // Extraction du premier paragraphe pour la description
        $firstParagraph = $this->extractFirstParagraph($content);

        // Génération du titre SEO
        $titles = $this->generateTitleVariations($keyword);

        // Génération de la meta description
        $descriptions = $this->generateDescriptionVariations($keyword, $firstParagraph);

        return [
            'title' => [
                'primary' => $titles[0],
                'variations' => $titles,
                'length' => strlen($titles[0]),
                'optimal' => strlen($titles[0]) <= 60,
            ],
            'description' => [
                'primary' => $descriptions[0],
                'variations' => $descriptions,
                'length' => strlen($descriptions[0]),
                'optimal' => strlen($descriptions[0]) <= 155,
            ],
            'keywords' => $this->extractMainKeywords($content, $keyword),
            'canonical' => '/' . slugify($keyword),
            'robots' => 'index, follow',
            'og' => [
                'title' => $titles[0],
                'description' => $descriptions[0],
                'type' => 'article',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $titles[0],
                'description' => $descriptions[0],
            ],
        ];
    }

    /**
     * Génère des variations de titres SEO
     */
    private function generateTitleVariations(string $keyword): array
    {
        $keyword = ucfirst($keyword);
        $year = date('Y');

        return [
            "{$keyword} : Guide Complet {$year} | Conseils d'Expert",
            "{$keyword} - Tout Savoir en {$year} | Guide Ultime",
            "Guide {$keyword} {$year} : Méthodes et Astuces Pro",
            "{$keyword} : Les Meilleures Pratiques {$year}",
            "Comment Réussir {$keyword} ? Guide Expert {$year}",
        ];
    }

    /**
     * Génère des variations de meta descriptions
     */
    private function generateDescriptionVariations(string $keyword, string $context): array
    {
        $keyword = ucfirst($keyword);

        return [
            "Découvrez notre guide complet sur {$keyword}. Conseils d'experts, méthodes éprouvées et astuces pratiques pour réussir. Mis à jour en " . date('Y') . ".",
            "Tout ce qu'il faut savoir sur {$keyword} : guide expert avec exemples concrets, erreurs à éviter et meilleures pratiques du moment.",
            "Maîtrisez {$keyword} grâce à notre guide détaillé. Stratégies efficaces, conseils pro et ressources complètes pour atteindre vos objectifs.",
            "{$keyword} expliqué simplement. Guide pratique avec étapes détaillées, FAQ et recommandations d'experts du secteur.",
        ];
    }

    /**
     * Extrait le premier paragraphe
     */
    private function extractFirstParagraph(string $content): string
    {
        // Supprime les titres markdown
        $content = preg_replace('/^#+\s+.+$/m', '', $content);
        $paragraphs = preg_split('/\n\n+/', trim($content));

        return isset($paragraphs[0]) ? trim($paragraphs[0]) : '';
    }

    /**
     * Extrait les mots-clés principaux
     */
    private function extractMainKeywords(string $content, string $mainKeyword): array
    {
        $words = str_word_count(strtolower($content), 1);
        $wordCounts = array_count_values($words);

        // Filtrage des stop words
        $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'en', 'et', 'ou', 'à', 'pour', 'par', 'sur', 'avec', 'dans', 'ce', 'ces', 'cette', 'est', 'sont', 'plus', 'tout', 'tous', 'que', 'qui', 'vous', 'votre', 'être', 'avoir'];

        foreach ($stopWords as $sw) {
            unset($wordCounts[$sw]);
        }

        // Filtrage par longueur
        $wordCounts = array_filter($wordCounts, function ($word) {
            return strlen($word) > 3;
        }, ARRAY_FILTER_USE_KEY);

        arsort($wordCounts);

        return array_slice(array_keys($wordCounts), 0, 10);
    }

    /**
     * Génère le schema markup JSON-LD
     */
    public function generateSchema(string $content, string $keyword): array
    {
        $wordCount = word_count($content);

        return [
            'article' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => ucfirst($keyword) . ' : Guide Complet ' . date('Y'),
                'description' => 'Guide complet sur ' . $keyword,
                'wordCount' => $wordCount,
                'datePublished' => date('c'),
                'dateModified' => date('c'),
                'author' => [
                    '@type' => 'Person',
                    'name' => 'Expert SEO',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'SEO Agency Pro',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => '/images/logo.png',
                    ],
                ],
            ],
            'faq' => $this->generateFAQSchema($keyword),
            'breadcrumb' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Accueil',
                        'item' => '/',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Blog',
                        'item' => '/blog',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => ucfirst($keyword),
                    ],
                ],
            ],
        ];
    }

    /**
     * Génère le schema FAQ
     */
    private function generateFAQSchema(string $keyword): array
    {
        $questions = [
            "Qu'est-ce que {$keyword} ?",
            "Comment fonctionne {$keyword} ?",
            "Quels sont les avantages de {$keyword} ?",
        ];

        $faqItems = [];
        foreach ($questions as $q) {
            $faqItems[] = [
                '@type' => 'Question',
                'name' => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Réponse détaillée à la question sur ' . $keyword . '.',
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems,
        ];
    }

    /**
     * Suggère des liens internes
     */
    public function suggestInternalLinks(string $keyword): array
    {
        return [
            [
                'anchor' => 'guide complet',
                'url' => '/guides/' . slugify($keyword),
                'context' => 'Renvoi vers un guide plus détaillé',
                'rel' => 'related',
            ],
            [
                'anchor' => 'nos services',
                'url' => '/services',
                'context' => 'Page de services associés',
                'rel' => 'service',
            ],
            [
                'anchor' => 'études de cas',
                'url' => '/cas-clients',
                'context' => 'Exemples concrets de réussite',
                'rel' => 'example',
            ],
            [
                'anchor' => 'contactez nos experts',
                'url' => '/contact',
                'context' => 'Appel à l\'action pour conversion',
                'rel' => 'contact',
            ],
            [
                'anchor' => 'articles similaires',
                'url' => '/blog/category/' . slugify($keyword),
                'context' => 'Navigation vers contenus connexes',
                'rel' => 'category',
            ],
        ];
    }

    /**
     * Génère des ancres optimisées
     */
    public function generateAnchors(string $keyword): array
    {
        return [
            'exact_match' => $keyword,
            'partial_match' => [
                'guide ' . $keyword,
                $keyword . ' professionnel',
                'tout sur ' . $keyword,
            ],
            'branded' => [
                'notre expertise ' . $keyword,
                'solution ' . $keyword,
            ],
            'generic' => [
                'en savoir plus',
                'découvrir',
                'voir le guide',
            ],
            'long_tail' => [
                'comment réussir avec ' . $keyword,
                'les meilleures pratiques ' . $keyword,
            ],
            'recommendation' => 'Variez les ancres : 30% exact match, 40% partial match, 30% autres',
        ];
    }

    /**
     * Analyse la lisibilité
     */
    public function analyzeReadability(string $content): array
    {
        $sentences = preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($content);
        $paragraphs = substr_count($content, "\n\n") + 1;
        $avgWordsPerSentence = $words / max(1, count($sentences));
        $avgWordsPerParagraph = $words / max(1, $paragraphs);

        $score = 100;
        $issues = [];

        if ($avgWordsPerSentence > 25) {
            $score -= 15;
            $issues[] = 'Phrases trop longues (moyenne > 25 mots)';
        }

        if ($avgWordsPerParagraph > 150) {
            $score -= 10;
            $issues[] = 'Paragraphes trop denses';
        }

        if (!preg_match('/^## /m', $content)) {
            $score -= 10;
            $issues[] = 'Absence de sous-titres H2';
        }

        return [
            'score' => max(0, $score),
            'grade' => $this->getReadabilityGrade($score),
            'avg_sentence_length' => round($avgWordsPerSentence, 1),
            'avg_paragraph_length' => round($avgWordsPerParagraph, 1),
            'total_sentences' => count($sentences),
            'total_paragraphs' => $paragraphs,
            'issues' => $issues,
            'suggestions' => $this->getReadabilitySuggestions($issues),
        ];
    }

    /**
     * Grade de lisibilité
     */
    private function getReadabilityGrade(int $score): string
    {
        if ($score >= 90) return 'Excellent';
        if ($score >= 70) return 'Bon';
        if ($score >= 50) return 'Acceptable';
        return 'À améliorer';
    }

    /**
     * Suggestions de lisibilité
     */
    private function getReadabilitySuggestions(array $issues): array
    {
        $suggestions = [];

        if (in_array('Phrases trop longues (moyenne > 25 mots)', $issues)) {
            $suggestions[] = 'Divisez les phrases longues en plusieurs phrases courtes';
        }

        if (in_array('Paragraphes trop denses', $issues)) {
            $suggestions[] = 'Aérez le texte avec des paragraphes plus courts';
        }

        if (in_array('Absence de sous-titres H2', $issues)) {
            $suggestions[] = 'Ajoutez des sous-titres pour structurer le contenu';
        }

        return $suggestions;
    }

    /**
     * Analyse des mots-clés
     */
    public function analyzeKeywords(string $content, string $keyword): array
    {
        $contentLower = strtolower($content);
        $keywordLower = strtolower($keyword);
        $wordCount = word_count($content);

        // Comptage du mot-clé principal
        $keywordCount = substr_count($contentLower, $keywordLower);
        $density = ($keywordCount / max(1, $wordCount)) * 100;

        // Vérification des emplacements
        $inTitle = preg_match('/^#\s+.*' . preg_quote($keywordLower, '/') . '/im', $content);
        $inFirstParagraph = str_contains(strtolower(substr($content, 0, 500)), $keywordLower);
        $inHeadings = preg_match('/^##+ .*' . preg_quote($keywordLower, '/') . '/im', $content);

        return [
            'main_keyword' => $keyword,
            'count' => $keywordCount,
            'density' => round($density, 2),
            'density_status' => $this->getDensityStatus($density),
            'placement' => [
                'in_title' => (bool)$inTitle,
                'in_first_paragraph' => $inFirstParagraph,
                'in_headings' => (bool)$inHeadings,
            ],
            'recommendations' => $this->getKeywordRecommendations($density, $inTitle, $inFirstParagraph, $inHeadings),
        ];
    }

    /**
     * Statut de la densité
     */
    private function getDensityStatus(float $density): string
    {
        if ($density < 0.5) return 'too_low';
        if ($density > 3) return 'too_high';
        return 'optimal';
    }

    /**
     * Recommandations mots-clés
     */
    private function getKeywordRecommendations(float $density, bool $inTitle, bool $inFirstParagraph, bool $inHeadings): array
    {
        $recommendations = [];

        if ($density < 0.5) {
            $recommendations[] = 'Augmentez la fréquence du mot-clé principal';
        } elseif ($density > 3) {
            $recommendations[] = 'Réduisez la densité du mot-clé pour éviter le keyword stuffing';
        }

        if (!$inTitle) {
            $recommendations[] = 'Intégrez le mot-clé dans le titre H1';
        }

        if (!$inFirstParagraph) {
            $recommendations[] = 'Mentionnez le mot-clé dans les 100 premiers mots';
        }

        if (!$inHeadings) {
            $recommendations[] = 'Utilisez le mot-clé dans au moins un sous-titre';
        }

        return $recommendations;
    }

    /**
     * Checklist SEO complète
     */
    public function getSEOChecklist(string $content, string $keyword): array
    {
        $wordCount = word_count($content);
        $keywordAnalysis = $this->analyzeKeywords($content, $keyword);
        $readability = $this->analyzeReadability($content);

        return [
            [
                'category' => 'Contenu',
                'items' => [
                    ['check' => 'Longueur minimale (1500+ mots)', 'status' => $wordCount >= 1500, 'value' => $wordCount . ' mots'],
                    ['check' => 'Mot-clé dans le titre', 'status' => $keywordAnalysis['placement']['in_title']],
                    ['check' => 'Mot-clé dans l\'introduction', 'status' => $keywordAnalysis['placement']['in_first_paragraph']],
                    ['check' => 'Densité mot-clé optimale (0.5-3%)', 'status' => $keywordAnalysis['density_status'] === 'optimal', 'value' => $keywordAnalysis['density'] . '%'],
                ],
            ],
            [
                'category' => 'Structure',
                'items' => [
                    ['check' => 'Présence de sous-titres H2', 'status' => (bool)preg_match('/^## /m', $content)],
                    ['check' => 'Présence de sous-titres H3', 'status' => (bool)preg_match('/^### /m', $content)],
                    ['check' => 'Listes à puces', 'status' => (bool)preg_match('/^[-*] /m', $content)],
                    ['check' => 'Paragraphes aérés', 'status' => $readability['avg_paragraph_length'] < 150],
                ],
            ],
            [
                'category' => 'EEAT',
                'items' => [
                    ['check' => 'Mentions d\'expertise', 'status' => str_contains(strtolower($content), 'expert')],
                    ['check' => 'Sources ou références', 'status' => str_contains(strtolower($content), 'selon') || str_contains(strtolower($content), 'étude')],
                    ['check' => 'Date de mise à jour', 'status' => str_contains($content, (string)date('Y'))],
                ],
            ],
            [
                'category' => 'Engagement',
                'items' => [
                    ['check' => 'Appel à l\'action', 'status' => str_contains(strtolower($content), 'contactez') || str_contains(strtolower($content), 'découvrez')],
                    ['check' => 'Section FAQ', 'status' => str_contains(strtolower($content), 'faq') || str_contains(strtolower($content), 'questions')],
                ],
            ],
        ];
    }
}
