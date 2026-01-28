<!-- SERP Analysis Form -->
<div class="page-grid">
    <div class="main-panel">
        <form id="serp-form" class="analysis-form" action="/serp/analyze" method="POST">
            <?= csrf_field() ?>

            <div class="form-section">
                <label for="query" class="form-label">
                    Requête à analyser
                    <span class="required">*</span>
                </label>
                <div class="input-with-button">
                    <input
                        type="text"
                        id="query"
                        name="query"
                        class="form-input"
                        placeholder="Ex: meilleur logiciel CRM 2024"
                        required
                    >
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        Analyser la SERP
                    </button>
                </div>
            </div>
        </form>

        <!-- Informations sur l'analyse SERP -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Top 10 Google</h3>
                    <p>Analyse des 10 premiers résultats Google pour votre requête</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Structure des contenus</h3>
                    <p>Identification des patterns de structure des concurrents</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Thématiques dominantes</h3>
                    <p>Extraction des sujets les plus traités dans la SERP</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20V10"/>
                        <path d="M18 20V4"/>
                        <path d="M6 20v-4"/>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Niveau de compétition</h3>
                    <p>Évaluation de la difficulté pour se positionner</p>
                </div>
            </div>
        </div>
    </div>

    <aside class="side-panel">
        <div class="panel-section">
            <h3>Analyses récentes</h3>
            <?php
            $recentAnalyses = array_slice($_SESSION['serp_results'] ?? [], 0, 5);
            if (empty($recentAnalyses)):
            ?>
                <p class="empty-text">Aucune analyse récente</p>
            <?php else: ?>
                <ul class="recent-list">
                    <?php foreach ($recentAnalyses as $analysis): ?>
                        <li>
                            <a href="/serp/results/<?= e($analysis['id']) ?>">
                                <span class="recent-query"><?= e(truncate($analysis['query'], 30)) ?></span>
                                <span class="recent-date"><?= time_ago($analysis['created_at']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="panel-section">
            <h3>Conseils</h3>
            <ul class="tips-list-simple">
                <li>Analysez les requêtes exactes de vos cibles</li>
                <li>Comparez les structures des top résultats</li>
                <li>Identifiez les lacunes des contenus existants</li>
                <li>Repérez les questions PAA à intégrer</li>
            </ul>
        </div>
    </aside>
</div>

<style>
.page-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: var(--spacing-6);
}

.main-panel {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
}

.input-with-button {
    display: flex;
    gap: var(--spacing-3);
}

.input-with-button .form-input {
    flex: 1;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-4);
    margin-top: var(--spacing-6);
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-3);
    padding: var(--spacing-4);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-100);
    color: var(--primary-600);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon svg {
    width: 20px;
    height: 20px;
}

.info-content h3 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--spacing-1);
}

.info-content p {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
}

.side-panel {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
}

.panel-section {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-5);
    box-shadow: var(--shadow-sm);
}

.panel-section h3 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--spacing-4);
}

.recent-list {
    list-style: none;
}

.recent-list li a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-2) 0;
    border-bottom: 1px solid var(--gray-100);
    font-size: var(--font-size-sm);
}

.recent-list li:last-child a {
    border-bottom: none;
}

.recent-query {
    color: var(--gray-700);
}

.recent-date {
    font-size: var(--font-size-xs);
    color: var(--gray-400);
}

.tips-list-simple {
    list-style: none;
}

.tips-list-simple li {
    position: relative;
    padding: var(--spacing-2) 0 var(--spacing-2) var(--spacing-5);
    font-size: var(--font-size-sm);
    color: var(--gray-600);
}

.tips-list-simple li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--primary-500);
    font-weight: bold;
}

.empty-text {
    font-size: var(--font-size-sm);
    color: var(--gray-400);
    text-align: center;
    padding: var(--spacing-4);
}

@media (max-width: 1024px) {
    .page-grid {
        grid-template-columns: 1fr;
    }

    .info-cards {
        grid-template-columns: 1fr;
    }
}
</style>
