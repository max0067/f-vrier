<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service d'analyse sémantique pour l'optimisation SEO
 */
class SemanticAnalyzer
{
    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Analyse sémantique complète d'un sujet
     */
    public function analyze(string $query): array
    {
        return [
            'query' => $query,
            'keywords' => $this->extractKeywords($query),
            'secondary_keywords' => $this->extractSecondaryKeywords($query),
            'questions' => $this->generateQuestions($query),
            'lexical_fields' => $this->getLexicalFields($query),
            'entities' => $this->extractEntities($query),
            'intent' => $this->analyzeIntent($query),
            'optimization_tips' => $this->getOptimizationTips($query),
        ];
    }

    /**
     * Analyse un contenu existant
     */
    public function analyzeContent(string $content): array
    {
        $wordCount = word_count($content);

        return [
            'word_count' => $wordCount,
            'reading_time' => reading_time($content),
            'keyword_density' => $this->calculateKeywordDensity($content),
            'readability_score' => $this->calculateReadability($content),
            'structure_analysis' => $this->analyzeStructure($content),
            'improvements' => $this->suggestImprovements($content),
            'seo_score' => $this->calculateSEOScore($content),
        ];
    }

    /**
     * Extrait les mots-clés principaux
     */
    private function extractKeywords(string $query): array
    {
        $words = array_filter(explode(' ', strtolower($query)));
        $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'en', 'et', 'ou', 'à', 'pour', 'par', 'sur', 'avec', 'dans', 'ce', 'ces', 'cette', 'est', 'sont', 'comment', 'pourquoi', 'quand', 'qui', 'que', 'quel', 'quelle'];

        $keywords = array_diff($words, $stopWords);
        $primaryKeywords = array_values($keywords);

        // Ajout de variations et synonymes simulés
        $expanded = [];
        foreach ($primaryKeywords as $kw) {
            $expanded[] = $kw;
            $expanded[] = $kw . ' professionnel';
            $expanded[] = 'meilleur ' . $kw;
            $expanded[] = $kw . ' 2024';
        }

        return array_slice($expanded, 0, 20);
    }

    /**
     * Extrait les mots-clés secondaires
     */
    private function extractSecondaryKeywords(string $query): array
    {
        $baseKeywords = $this->extractKeywords($query);

        $secondary = [];
        foreach ($baseKeywords as $kw) {
            $secondary[] = "guide " . $kw;
            $secondary[] = $kw . " conseil";
            $secondary[] = $kw . " comparatif";
            $secondary[] = $kw . " avis";
            $secondary[] = "prix " . $kw;
        }

        return array_unique(array_slice($secondary, 0, 15));
    }

    /**
     * Génère les questions PAA (People Also Ask)
     */
    private function generateQuestions(string $query): array
    {
        $subject = $query;

        return [
            "Qu'est-ce que {$subject} ?",
            "Comment fonctionne {$subject} ?",
            "Quels sont les avantages de {$subject} ?",
            "Combien coûte {$subject} ?",
            "Comment choisir {$subject} ?",
            "Quelles sont les meilleures pratiques pour {$subject} ?",
            "Quels sont les risques de {$subject} ?",
            "Comment optimiser {$subject} ?",
            "{$subject} est-il rentable ?",
            "Quelles alternatives à {$subject} ?",
        ];
    }

    /**
     * Identifie les champs lexicaux associés
     */
    private function getLexicalFields(string $query): array
    {
        // Simulation de champs lexicaux basés sur des patterns courants
        return [
            [
                'name' => 'Expertise',
                'terms' => ['expert', 'professionnel', 'spécialiste', 'compétence', 'savoir-faire', 'maîtrise', 'qualification'],
            ],
            [
                'name' => 'Action',
                'terms' => ['optimiser', 'améliorer', 'développer', 'mettre en place', 'créer', 'implémenter', 'déployer'],
            ],
            [
                'name' => 'Résultat',
                'terms' => ['performance', 'efficacité', 'rendement', 'succès', 'réussite', 'croissance', 'progression'],
            ],
            [
                'name' => 'Analyse',
                'terms' => ['étude', 'audit', 'évaluation', 'diagnostic', 'mesure', 'indicateur', 'métrique'],
            ],
            [
                'name' => 'Solution',
                'terms' => ['méthode', 'stratégie', 'technique', 'outil', 'approche', 'solution', 'système'],
            ],
        ];
    }

    /**
     * Extrait les entités nommées
     */
    private function extractEntities(string $query): array
    {
        return [
            'type' => 'Topic',
            'category' => $this->categorizeQuery($query),
            'related_topics' => $this->getRelatedTopics($query),
        ];
    }

    /**
     * Catégorise la requête
     */
    private function categorizeQuery(string $query): string
    {
        $categories = [
            'business' => ['entreprise', 'société', 'commerce', 'marketing', 'vente', 'client'],
            'tech' => ['logiciel', 'application', 'web', 'digital', 'numérique', 'technologie', 'site'],
            'finance' => ['prix', 'coût', 'budget', 'investissement', 'rentabilité', 'argent'],
            'education' => ['formation', 'apprendre', 'guide', 'tutoriel', 'cours', 'méthode'],
            'health' => ['santé', 'bien-être', 'médical', 'soin', 'thérapie'],
        ];

        $queryLower = strtolower($query);

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($queryLower, $keyword)) {
                    return $category;
                }
            }
        }

        return 'general';
    }

    /**
     * Obtient les sujets connexes
     */
    private function getRelatedTopics(string $query): array
    {
        return [
            $query . ' débutant',
            $query . ' avancé',
            $query . ' entreprise',
            $query . ' freelance',
            'tendances ' . $query . ' 2024',
        ];
    }

    /**
     * Analyse l'intention de recherche
     */
    private function analyzeIntent(string $query): array
    {
        $queryLower = strtolower($query);

        $informational = ['comment', 'pourquoi', 'qu\'est', 'définition', 'guide', 'comprendre'];
        $transactional = ['acheter', 'prix', 'tarif', 'commander', 'devis', 'abonnement'];
        $navigational = ['site', 'connexion', 'login', 'accès', 'contact'];
        $commercial = ['meilleur', 'comparatif', 'avis', 'test', 'vs', 'alternative'];

        $intent = 'informational';
        $confidence = 0.7;

        foreach ($transactional as $word) {
            if (str_contains($queryLower, $word)) {
                $intent = 'transactional';
                $confidence = 0.85;
                break;
            }
        }

        foreach ($commercial as $word) {
            if (str_contains($queryLower, $word)) {
                $intent = 'commercial';
                $confidence = 0.8;
                break;
            }
        }

        foreach ($navigational as $word) {
            if (str_contains($queryLower, $word)) {
                $intent = 'navigational';
                $confidence = 0.9;
                break;
            }
        }

        return [
            'type' => $intent,
            'confidence' => $confidence,
            'description' => $this->getIntentDescription($intent),
            'recommended_format' => $this->getRecommendedFormat($intent),
        ];
    }

    /**
     * Description de l'intention
     */
    private function getIntentDescription(string $intent): string
    {
        return match ($intent) {
            'informational' => 'L\'utilisateur cherche à s\'informer et comprendre le sujet',
            'transactional' => 'L\'utilisateur a une intention d\'achat ou d\'action',
            'navigational' => 'L\'utilisateur cherche à accéder à une ressource spécifique',
            'commercial' => 'L\'utilisateur compare les options avant décision',
            default => 'Intention de recherche mixte',
        };
    }

    /**
     * Format recommandé selon l'intention
     */
    private function getRecommendedFormat(string $intent): string
    {
        return match ($intent) {
            'informational' => 'Article de blog complet avec structure H2/H3, FAQ, et ressources',
            'transactional' => 'Page de vente avec CTAs, prix, et garanties clairement visibles',
            'navigational' => 'Page d\'atterrissage épurée avec accès direct à l\'action',
            'commercial' => 'Comparatif détaillé avec tableau, avantages/inconvénients, verdict',
            default => 'Contenu hybride adaptable',
        };
    }

    /**
     * Conseils d'optimisation
     */
    private function getOptimizationTips(string $query): array
    {
        return [
            [
                'category' => 'Structure',
                'tip' => 'Utilisez une hiérarchie H1 > H2 > H3 claire',
                'priority' => 'high',
            ],
            [
                'category' => 'Contenu',
                'tip' => 'Visez minimum 1500 mots pour un sujet compétitif',
                'priority' => 'high',
            ],
            [
                'category' => 'EEAT',
                'tip' => 'Ajoutez des citations d\'experts et sources fiables',
                'priority' => 'high',
            ],
            [
                'category' => 'UX',
                'tip' => 'Intégrez une table des matières cliquable',
                'priority' => 'medium',
            ],
            [
                'category' => 'Rich Snippets',
                'tip' => 'Ajoutez une section FAQ pour viser la position zéro',
                'priority' => 'medium',
            ],
            [
                'category' => 'Maillage',
                'tip' => 'Prévoyez 3-5 liens internes vers du contenu connexe',
                'priority' => 'medium',
            ],
            [
                'category' => 'Média',
                'tip' => 'Incluez au moins 2 images optimisées avec alt text',
                'priority' => 'medium',
            ],
        ];
    }

    /**
     * Calcule la densité des mots-clés
     */
    private function calculateKeywordDensity(string $content): array
    {
        $words = str_word_count(strtolower($content), 1);
        $totalWords = count($words);
        $wordCounts = array_count_values($words);

        arsort($wordCounts);
        $topWords = array_slice($wordCounts, 0, 10, true);

        $density = [];
        foreach ($topWords as $word => $count) {
            if (strlen($word) > 3) {
                $density[$word] = round(($count / $totalWords) * 100, 2);
            }
        }

        return $density;
    }

    /**
     * Calcule le score de lisibilité
     */
    private function calculateReadability(string $content): array
    {
        $sentences = preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($content);
        $avgWordsPerSentence = $words / max(1, count($sentences));

        // Score simple basé sur la longueur moyenne des phrases
        $score = 100;
        if ($avgWordsPerSentence > 25) $score -= 20;
        if ($avgWordsPerSentence > 30) $score -= 20;
        if ($avgWordsPerSentence < 10) $score -= 10;

        return [
            'score' => max(0, $score),
            'avg_sentence_length' => round($avgWordsPerSentence, 1),
            'total_sentences' => count($sentences),
            'recommendation' => $avgWordsPerSentence > 25
                ? 'Raccourcissez vos phrases pour améliorer la lisibilité'
                : 'Bonne lisibilité générale',
        ];
    }

    /**
     * Analyse la structure du contenu
     */
    private function analyzeStructure(string $content): array
    {
        preg_match_all('/^#{1,6}\s+.+$/m', $content, $headings);
        preg_match_all('/^[-*]\s+.+$/m', $content, $lists);
        preg_match_all('/\[.+\]\(.+\)/', $content, $links);

        return [
            'headings_count' => count($headings[0]),
            'lists_count' => count($lists[0]),
            'links_count' => count($links[0]),
            'paragraphs' => substr_count($content, "\n\n"),
            'has_intro' => true,
            'has_conclusion' => str_contains(strtolower($content), 'conclusion'),
        ];
    }

    /**
     * Suggère des améliorations
     */
    private function suggestImprovements(string $content): array
    {
        $improvements = [];
        $wordCount = word_count($content);

        if ($wordCount < 1000) {
            $improvements[] = [
                'type' => 'length',
                'message' => 'Enrichissez le contenu pour atteindre au moins 1500 mots',
                'priority' => 'high',
            ];
        }

        if (!preg_match('/^## /m', $content)) {
            $improvements[] = [
                'type' => 'structure',
                'message' => 'Ajoutez des sous-titres H2 pour structurer le contenu',
                'priority' => 'high',
            ];
        }

        if (!preg_match('/^[-*] /m', $content)) {
            $improvements[] = [
                'type' => 'format',
                'message' => 'Intégrez des listes à puces pour faciliter la lecture',
                'priority' => 'medium',
            ];
        }

        return $improvements;
    }

    /**
     * Calcule le score SEO global
     */
    private function calculateSEOScore(string $content): int
    {
        $score = 50;

        $wordCount = word_count($content);
        if ($wordCount >= 1500) $score += 15;
        elseif ($wordCount >= 1000) $score += 10;

        if (preg_match_all('/^## /m', $content) >= 3) $score += 10;
        if (preg_match_all('/^### /m', $content) >= 2) $score += 5;
        if (preg_match('/^[-*] /m', $content)) $score += 5;
        if (str_contains(strtolower($content), 'faq')) $score += 5;

        return min(100, $score);
    }
}
