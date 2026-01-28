<!-- Content History -->
<div class="history-header">
    <div class="history-title">
        <h2>Historique des contenus générés</h2>
        <p><?= count($history) ?> contenu(s) généré(s)</p>
    </div>
    <a href="/content" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouveau contenu
    </a>
</div>

<?php if (empty($history)): ?>
    <div class="empty-history">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
        </svg>
        <h3>Aucun contenu généré</h3>
        <p>Commencez par générer votre premier contenu optimisé SEO.</p>
        <a href="/content" class="btn btn-primary">Créer mon premier contenu</a>
    </div>
<?php else: ?>
    <div class="history-grid">
        <?php foreach ($history as $item): ?>
            <div class="history-card">
                <div class="history-card-header">
                    <h3><?= e(truncate($item['prompt'], 60)) ?></h3>
                    <span class="history-date"><?= time_ago($item['created_at']) ?></span>
                </div>

                <div class="history-card-meta">
                    <?php if (isset($item['result']['content']['versions'][0])): ?>
                        <?php $version = $item['result']['content']['versions'][0]; ?>
                        <span class="meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            </svg>
                            <?= number_format($version['word_count'] ?? 0) ?> mots
                        </span>
                        <span class="meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <?= $version['reading_time'] ?? 0 ?> min
                        </span>
                        <span class="meta-item score">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20V10"/>
                                <path d="M18 20V4"/>
                                <path d="M6 20v-4"/>
                            </svg>
                            Score: <?= $version['score']['total'] ?? 0 ?>/100
                        </span>
                    <?php endif; ?>
                </div>

                <div class="history-card-actions">
                    <a href="/content/<?= e($item['id']) ?>" class="btn btn-ghost btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Voir
                    </a>
                    <button class="btn btn-ghost btn-sm" onclick="copyContent('<?= e($item['id']) ?>')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copier
                    </button>
                    <form action="/content/<?= e($item['id']) ?>" method="POST" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-ghost btn-sm text-danger" onclick="return confirm('Supprimer ce contenu ?')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-6);
}

.history-title h2 {
    font-size: var(--font-size-xl);
    font-weight: 600;
    color: var(--gray-900);
}

.history-title p {
    font-size: var(--font-size-sm);
    color: var(--gray-500);
    margin-top: var(--spacing-1);
}

.history-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--spacing-4);
}

.history-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-5);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-fast);
}

.history-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.history-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-3);
}

.history-card-header h3 {
    font-size: var(--font-size-base);
    font-weight: 600;
    color: var(--gray-900);
    flex: 1;
    margin-right: var(--spacing-3);
}

.history-date {
    font-size: var(--font-size-xs);
    color: var(--gray-400);
    white-space: nowrap;
}

.history-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-3);
    margin-bottom: var(--spacing-4);
    padding-bottom: var(--spacing-4);
    border-bottom: 1px solid var(--gray-100);
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-1);
    font-size: var(--font-size-sm);
    color: var(--gray-500);
}

.meta-item svg {
    width: 14px;
    height: 14px;
}

.meta-item.score {
    color: var(--primary-600);
    font-weight: 500;
}

.history-card-actions {
    display: flex;
    gap: var(--spacing-2);
}

.text-danger {
    color: var(--error-500);
}

.text-danger:hover {
    color: var(--error-600);
    background: #FEE2E2;
}

.empty-history {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-12);
    text-align: center;
    box-shadow: var(--shadow-sm);
}

.empty-history svg {
    width: 64px;
    height: 64px;
    color: var(--gray-300);
    margin-bottom: var(--spacing-4);
}

.empty-history h3 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: var(--spacing-2);
}

.empty-history p {
    font-size: var(--font-size-base);
    color: var(--gray-500);
    margin-bottom: var(--spacing-6);
}

@media (max-width: 768px) {
    .history-header {
        flex-direction: column;
        gap: var(--spacing-4);
        align-items: flex-start;
    }

    .history-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function copyContent(id) {
    // Récupérer le contenu depuis la session stockée côté client si disponible
    const history = <?= json_encode($history) ?>;
    const item = history.find(h => h.id === id);
    if (item && item.result && item.result.content && item.result.content.versions[0]) {
        SEOApp.utils.copyToClipboard(item.result.content.versions[0].content);
    }
}
</script>
