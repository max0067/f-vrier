<!-- Semantic Analysis -->
<div class="semantic-layout">
    <div class="main-panel">
        <form id="semantic-form" action="/semantic/analyze" method="POST">
            <?= csrf_field() ?>

            <div class="form-tabs">
                <button type="button" class="form-tab active" data-target="query-input">Analyser un sujet</button>
                <button type="button" class="form-tab" data-target="content-input">Analyser un contenu</button>
            </div>

            <div id="query-input" class="form-tab-content active">
                <div class="form-section">
                    <label for="query" class="form-label">Sujet / Mot-clé à analyser</label>
                    <input
                        type="text"
                        id="query"
                        name="query"
                        class="form-input"
                        placeholder="Ex: marketing automation B2B"
                    >
                </div>
            </div>

            <div id="content-input" class="form-tab-content">
                <div class="form-section">
                    <label for="content" class="form-label">Contenu à analyser</label>
                    <textarea
                        id="content"
                        name="content"
                        class="form-textarea"
                        placeholder="Collez votre contenu ici pour une analyse sémantique complète..."
                        rows="10"
                    ></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Lancer l'analyse
                </button>
            </div>
        </form>
    </div>

    <div class="results-panel">
        <?php if (isset($_SESSION['semantic_result'])): $result = $_SESSION['semantic_result']; unset($_SESSION['semantic_result']); ?>

            <?php if (($result['type'] ?? '') === 'query'): ?>
                <!-- Query Analysis Results -->
                <div class="result-card">
                    <h3>Intention de recherche</h3>
                    <div class="intent-display">
                        <span class="intent-badge intent-<?= e($result['intent']['type']) ?>">
                            <?= e(ucfirst($result['intent']['type'])) ?>
                        </span>
                        <span class="intent-confidence"><?= round($result['intent']['confidence'] * 100) ?>% confiance</span>
                    </div>
                    <p class="intent-desc"><?= e($result['intent']['description']) ?></p>
                    <p class="intent-format"><strong>Format recommandé :</strong> <?= e($result['intent']['recommended_format']) ?></p>
                </div>

                <div class="result-card">
                    <h3>Mots-clés principaux</h3>
                    <div class="keywords-cloud">
                        <?php foreach (array_slice($result['keywords'], 0, 15) as $kw): ?>
                            <span class="keyword-tag"><?= e($kw) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="result-card">
                    <h3>Mots-clés secondaires</h3>
                    <div class="keywords-cloud secondary">
                        <?php foreach (array_slice($result['secondary_keywords'], 0, 10) as $kw): ?>
                            <span class="keyword-tag"><?= e($kw) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="result-card">
                    <h3>Questions PAA</h3>
                    <ul class="questions-list">
                        <?php foreach ($result['questions'] as $q): ?>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                <?= e($q) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="result-card">
                    <h3>Champs lexicaux</h3>
                    <?php foreach ($result['lexical_fields'] as $field): ?>
                        <div class="lexical-field">
                            <h4><?= e($field['name']) ?></h4>
                            <div class="lexical-terms">
                                <?php foreach ($field['terms'] as $term): ?>
                                    <span><?= e($term) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- Content Analysis Results -->
                <div class="result-card">
                    <h3>Statistiques du contenu</h3>
                    <div class="stats-row">
                        <div class="stat-item">
                            <span class="stat-value"><?= number_format($result['word_count']) ?></span>
                            <span class="stat-label">mots</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $result['reading_time'] ?></span>
                            <span class="stat-label">min lecture</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $result['seo_score'] ?>/100</span>
                            <span class="stat-label">Score SEO</span>
                        </div>
                    </div>
                </div>

                <div class="result-card">
                    <h3>Lisibilité</h3>
                    <div class="readability-score">
                        <div class="score-circle <?= $result['readability_score']['score'] >= 70 ? 'good' : 'warning' ?>">
                            <?= $result['readability_score']['score'] ?>
                        </div>
                        <div class="score-details">
                            <p><strong>Grade :</strong> <?= e($result['readability_score']['grade']) ?></p>
                            <p><strong>Longueur moyenne des phrases :</strong> <?= $result['readability_score']['avg_sentence_length'] ?> mots</p>
                            <p class="recommendation"><?= e($result['readability_score']['recommendation']) ?></p>
                        </div>
                    </div>
                </div>

                <?php if (!empty($result['improvements'])): ?>
                <div class="result-card">
                    <h3>Améliorations suggérées</h3>
                    <ul class="improvements-list">
                        <?php foreach ($result['improvements'] as $improvement): ?>
                            <li class="priority-<?= e($improvement['priority']) ?>">
                                <span class="priority-badge"><?= e($improvement['priority']) ?></span>
                                <?= e($improvement['message']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

            <?php endif; ?>

        <?php else: ?>
            <div class="empty-results">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <h3>Analyse Sémantique</h3>
                <p>Analysez un sujet pour obtenir les mots-clés, questions PAA et champs lexicaux associés.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.semantic-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: var(--spacing-6);
    align-items: start;
}

.main-panel {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: calc(var(--header-height) + var(--spacing-6));
}

.form-tabs {
    display: flex;
    gap: var(--spacing-2);
    margin-bottom: var(--spacing-4);
    border-bottom: 1px solid var(--gray-200);
    padding-bottom: var(--spacing-2);
}

.form-tab {
    padding: var(--spacing-2) var(--spacing-4);
    background: none;
    border: none;
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: var(--gray-500);
    cursor: pointer;
    border-radius: var(--radius-md);
    transition: all var(--transition-fast);
}

.form-tab:hover {
    color: var(--gray-700);
}

.form-tab.active {
    background: var(--primary-50);
    color: var(--primary-700);
}

.form-tab-content {
    display: none;
}

.form-tab-content.active {
    display: block;
}

.results-panel {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
}

.result-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-5);
    box-shadow: var(--shadow-sm);
}

.result-card h3 {
    font-size: var(--font-size-base);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--spacing-4);
    padding-bottom: var(--spacing-2);
    border-bottom: 1px solid var(--gray-100);
}

.intent-display {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    margin-bottom: var(--spacing-2);
}

.intent-badge {
    padding: var(--spacing-1) var(--spacing-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
    font-weight: 600;
}

.intent-informational { background: #DBEAFE; color: #1D4ED8; }
.intent-transactional { background: #DCFCE7; color: #15803D; }
.intent-commercial { background: #FEF3C7; color: #B45309; }
.intent-navigational { background: #F3E8FF; color: #7C3AED; }

.intent-confidence {
    font-size: var(--font-size-sm);
    color: var(--gray-500);
}

.intent-desc {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: var(--spacing-2);
}

.intent-format {
    font-size: var(--font-size-sm);
    color: var(--gray-700);
    background: var(--gray-50);
    padding: var(--spacing-3);
    border-radius: var(--radius-md);
}

.keywords-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-2);
}

.keyword-tag {
    padding: var(--spacing-1) var(--spacing-3);
    background: var(--primary-50);
    color: var(--primary-700);
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
}

.keywords-cloud.secondary .keyword-tag {
    background: var(--gray-100);
    color: var(--gray-700);
}

.questions-list {
    list-style: none;
}

.questions-list li {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-2);
    padding: var(--spacing-2) 0;
    font-size: var(--font-size-sm);
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
}

.questions-list li:last-child {
    border-bottom: none;
}

.questions-list svg {
    width: 18px;
    height: 18px;
    color: var(--primary-500);
    flex-shrink: 0;
    margin-top: 2px;
}

.lexical-field {
    margin-bottom: var(--spacing-4);
}

.lexical-field:last-child {
    margin-bottom: 0;
}

.lexical-field h4 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: var(--spacing-2);
}

.lexical-terms {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-1);
}

.lexical-terms span {
    padding: 2px 8px;
    background: var(--gray-100);
    color: var(--gray-600);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
}

.stats-row {
    display: flex;
    gap: var(--spacing-6);
}

.stat-item {
    text-align: center;
}

.stat-item .stat-value {
    display: block;
    font-size: var(--font-size-2xl);
    font-weight: 700;
    color: var(--gray-900);
}

.stat-item .stat-label {
    font-size: var(--font-size-sm);
    color: var(--gray-500);
}

.readability-score {
    display: flex;
    gap: var(--spacing-4);
    align-items: flex-start;
}

.score-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-xl);
    font-weight: 700;
    flex-shrink: 0;
}

.score-circle.good {
    background: #DCFCE7;
    color: #15803D;
}

.score-circle.warning {
    background: #FEF3C7;
    color: #B45309;
}

.score-details p {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: var(--spacing-1);
}

.score-details .recommendation {
    color: var(--gray-500);
    font-style: italic;
}

.improvements-list {
    list-style: none;
}

.improvements-list li {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    padding: var(--spacing-3);
    background: var(--gray-50);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-2);
    font-size: var(--font-size-sm);
}

.priority-badge {
    padding: 2px 8px;
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
}

.priority-high .priority-badge {
    background: #FEE2E2;
    color: #991B1B;
}

.priority-medium .priority-badge {
    background: #FEF3C7;
    color: #92400E;
}

.empty-results {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-10);
    box-shadow: var(--shadow-sm);
    text-align: center;
    color: var(--gray-400);
}

.empty-results svg {
    width: 48px;
    height: 48px;
    margin-bottom: var(--spacing-4);
}

.empty-results h3 {
    font-size: var(--font-size-lg);
    color: var(--gray-600);
    margin-bottom: var(--spacing-2);
}

@media (max-width: 1024px) {
    .semantic-layout {
        grid-template-columns: 1fr;
    }

    .main-panel {
        position: static;
    }
}
</style>

<script>
document.querySelectorAll('.form-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.form-tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.target).classList.add('active');
    });
});
</script>
