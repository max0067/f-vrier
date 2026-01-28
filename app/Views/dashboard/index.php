<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= number_format($stats['contents_generated']) ?></span>
            <span class="stat-label">Contenus générés</span>
        </div>
        <div class="stat-trend stat-trend-up">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
            </svg>
            +12%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= number_format($stats['serp_analyses']) ?></span>
            <span class="stat-label">Analyses SERP</span>
        </div>
        <div class="stat-trend stat-trend-up">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
            </svg>
            +8%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= number_format($stats['images_created']) ?></span>
            <span class="stat-label">Images créées</span>
        </div>
        <div class="stat-trend stat-trend-up">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
            </svg>
            +25%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20V10"/>
                <path d="M18 20V4"/>
                <path d="M6 20v-4"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= number_format($stats['words_written']) ?></span>
            <span class="stat-label">Mots écrits</span>
        </div>
        <div class="stat-trend stat-trend-up">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
            </svg>
            +15%
        </div>
    </div>
</div>

<!-- Quick Actions -->
<section class="section">
    <h2 class="section-title">Actions rapides</h2>
    <div class="quick-actions">
        <a href="/content" class="quick-action-card">
            <div class="quick-action-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </div>
            <div class="quick-action-content">
                <h3>Nouveau contenu</h3>
                <p>Générer un article optimisé SEO</p>
            </div>
        </a>

        <a href="/serp" class="quick-action-card">
            <div class="quick-action-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </div>
            <div class="quick-action-content">
                <h3>Analyse SERP</h3>
                <p>Analyser les résultats Google</p>
            </div>
        </a>

        <a href="/images" class="quick-action-card">
            <div class="quick-action-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <div class="quick-action-content">
                <h3>Image IA</h3>
                <p>Créer une image réaliste</p>
            </div>
        </a>

        <a href="/seo" class="quick-action-card">
            <div class="quick-action-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <div class="quick-action-content">
                <h3>SEO Technique</h3>
                <p>Optimiser méta et maillage</p>
            </div>
        </a>
    </div>
</section>

<!-- Recent Content & Activity -->
<div class="dashboard-grid">
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Contenus récents</h2>
            <a href="/content/history" class="section-link">Voir tout</a>
        </div>
        <div class="content-list">
            <?php if (empty($recentContents)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <p>Aucun contenu généré</p>
                    <a href="/content" class="btn btn-primary btn-sm">Créer mon premier contenu</a>
                </div>
            <?php else: ?>
                <?php foreach ($recentContents as $content): ?>
                    <div class="content-item">
                        <div class="content-info">
                            <h4><?= e(truncate($content['prompt'] ?? 'Sans titre', 50)) ?></h4>
                            <span class="content-meta"><?= time_ago($content['created_at'] ?? time()) ?></span>
                        </div>
                        <div class="content-actions">
                            <a href="/content/<?= e($content['id'] ?? '') ?>" class="btn btn-ghost btn-sm">Voir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Conseils EEAT</h2>
        </div>
        <div class="tips-list">
            <div class="tip-card">
                <div class="tip-icon tip-icon-expertise">E</div>
                <div class="tip-content">
                    <h4>Expertise</h4>
                    <p>Démontrez votre maîtrise du sujet avec des données précises et des exemples concrets.</p>
                </div>
            </div>
            <div class="tip-card">
                <div class="tip-icon tip-icon-experience">E</div>
                <div class="tip-content">
                    <h4>Expérience</h4>
                    <p>Partagez des retours d'expérience personnels pour humaniser votre contenu.</p>
                </div>
            </div>
            <div class="tip-card">
                <div class="tip-icon tip-icon-authority">A</div>
                <div class="tip-content">
                    <h4>Autorité</h4>
                    <p>Citez des sources fiables et établissez votre légitimité sur le sujet.</p>
                </div>
            </div>
            <div class="tip-card">
                <div class="tip-icon tip-icon-trust">T</div>
                <div class="tip-content">
                    <h4>Fiabilité</h4>
                    <p>Soyez transparent, précis et fournissez des informations vérifiables.</p>
                </div>
            </div>
        </div>
    </section>
</div>
