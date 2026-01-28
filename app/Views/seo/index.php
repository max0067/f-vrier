<!-- SEO Technical Tools -->
<div class="seo-layout">
    <div class="seo-form-panel">
        <form id="seo-form" action="/seo/meta" method="POST">
            <?= csrf_field() ?>

            <div class="form-section">
                <label for="keyword" class="form-label">
                    Mot-clé principal
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    class="form-input"
                    placeholder="Ex: logiciel gestion projet"
                    required
                >
            </div>

            <div class="form-section">
                <label for="content" class="form-label">Contenu à optimiser (optionnel)</label>
                <textarea
                    id="content"
                    name="content"
                    class="form-textarea"
                    placeholder="Collez votre contenu ici pour une analyse SEO complète..."
                    rows="8"
                ></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20V10"/>
                        <path d="M18 20V4"/>
                        <path d="M6 20v-4"/>
                    </svg>
                    Générer l'optimisation SEO
                </button>
            </div>
        </form>
    </div>

    <div class="seo-results-panel">
        <?php if (isset($seoResult)): ?>
            <!-- Meta Tags -->
            <?php if (isset($seoResult['meta'])): ?>
            <div class="result-section">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    Meta Tags
                </h3>

                <div class="meta-item">
                    <label>Titre SEO</label>
                    <div class="meta-variations">
                        <?php foreach ($seoResult['meta']['title']['variations'] as $i => $title): ?>
                        <div class="meta-variation <?= $i === 0 ? 'primary' : '' ?>">
                            <span class="meta-text"><?= e($title) ?></span>
                            <span class="meta-count"><?= strlen($title) ?>/60</span>
                            <button type="button" class="copy-btn" onclick="SEOApp.utils.copyToClipboard('<?= e($title) ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="meta-item">
                    <label>Meta Description</label>
                    <div class="meta-variations">
                        <?php foreach ($seoResult['meta']['description']['variations'] as $i => $desc): ?>
                        <div class="meta-variation <?= $i === 0 ? 'primary' : '' ?>">
                            <span class="meta-text"><?= e($desc) ?></span>
                            <span class="meta-count"><?= strlen($desc) ?>/155</span>
                            <button type="button" class="copy-btn" onclick="SEOApp.utils.copyToClipboard('<?= e($desc) ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Internal Links -->
            <?php if (isset($seoResult['internal_links'])): ?>
            <div class="result-section">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    Maillage Interne
                </h3>
                <div class="links-table">
                    <div class="links-header">
                        <span>Ancre suggérée</span>
                        <span>URL cible</span>
                        <span>Contexte</span>
                    </div>
                    <?php foreach ($seoResult['internal_links'] as $link): ?>
                    <div class="links-row">
                        <span class="link-anchor"><?= e($link['anchor']) ?></span>
                        <span class="link-url"><?= e($link['url']) ?></span>
                        <span class="link-context"><?= e($link['context']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Anchors -->
            <?php if (isset($seoResult['anchors'])): ?>
            <div class="result-section">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="5" r="3"/>
                        <line x1="12" y1="22" x2="12" y2="8"/>
                        <path d="M5 12H2a10 10 0 0 0 20 0h-3"/>
                    </svg>
                    Ancres Optimisées
                </h3>
                <div class="anchors-grid">
                    <div class="anchor-category">
                        <h4>Exact Match (30%)</h4>
                        <span class="anchor-tag primary"><?= e($seoResult['anchors']['exact_match']) ?></span>
                    </div>
                    <div class="anchor-category">
                        <h4>Partial Match (40%)</h4>
                        <?php foreach ($seoResult['anchors']['partial_match'] as $anchor): ?>
                        <span class="anchor-tag"><?= e($anchor) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="anchor-category">
                        <h4>Generic (30%)</h4>
                        <?php foreach ($seoResult['anchors']['generic'] as $anchor): ?>
                        <span class="anchor-tag secondary"><?= e($anchor) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Checklist -->
            <?php if (isset($seoResult['checklist'])): ?>
            <div class="result-section">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Checklist SEO
                </h3>
                <?php foreach ($seoResult['checklist'] as $category): ?>
                <div class="checklist-category">
                    <h4><?= e($category['category']) ?></h4>
                    <ul class="checklist-items">
                        <?php foreach ($category['items'] as $item): ?>
                        <li class="<?= $item['status'] ? 'checked' : 'unchecked' ?>">
                            <span class="check-icon">
                                <?php if ($item['status']): ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <?php else: ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                <?php endif; ?>
                            </span>
                            <span class="check-text"><?= e($item['check']) ?></span>
                            <?php if (isset($item['value'])): ?>
                            <span class="check-value"><?= e($item['value']) ?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-results">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20V10"/>
                    <path d="M18 20V4"/>
                    <path d="M6 20v-4"/>
                </svg>
                <h3>Optimisation SEO</h3>
                <p>Entrez un mot-clé pour générer des recommandations SEO complètes.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.seo-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: var(--spacing-6);
    align-items: start;
}

.seo-form-panel {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: calc(var(--header-height) + var(--spacing-6));
}

.seo-results-panel {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
}

.result-section {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
}

.result-section h3 {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    font-size: var(--font-size-base);
    font-weight: 600;
    margin-bottom: var(--spacing-4);
    padding-bottom: var(--spacing-3);
    border-bottom: 1px solid var(--gray-200);
}

.result-section h3 svg {
    width: 20px;
    height: 20px;
    color: var(--primary-500);
}

.meta-item {
    margin-bottom: var(--spacing-4);
}

.meta-item label {
    display: block;
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: var(--spacing-2);
}

.meta-variations {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
}

.meta-variation {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    padding: var(--spacing-3);
    background: var(--gray-50);
    border-radius: var(--radius-md);
    font-size: var(--font-size-sm);
}

.meta-variation.primary {
    background: var(--primary-50);
    border: 1px solid var(--primary-200);
}

.meta-text {
    flex: 1;
    color: var(--gray-700);
}

.meta-count {
    font-size: var(--font-size-xs);
    color: var(--gray-400);
    white-space: nowrap;
}

.copy-btn {
    padding: var(--spacing-1);
    background: none;
    border: none;
    color: var(--gray-400);
    cursor: pointer;
    border-radius: var(--radius-sm);
}

.copy-btn:hover {
    background: var(--gray-200);
    color: var(--gray-600);
}

.copy-btn svg {
    width: 16px;
    height: 16px;
}

.links-table {
    font-size: var(--font-size-sm);
}

.links-header {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: var(--spacing-3);
    padding: var(--spacing-2) var(--spacing-3);
    background: var(--gray-100);
    border-radius: var(--radius-md);
    font-weight: 500;
    color: var(--gray-600);
    margin-bottom: var(--spacing-2);
}

.links-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: var(--spacing-3);
    padding: var(--spacing-3);
    border-bottom: 1px solid var(--gray-100);
}

.link-anchor {
    color: var(--primary-600);
    font-weight: 500;
}

.link-url {
    color: var(--gray-500);
    font-family: monospace;
    font-size: var(--font-size-xs);
}

.link-context {
    color: var(--gray-600);
}

.anchors-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-4);
}

.anchor-category h4 {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    margin-bottom: var(--spacing-2);
}

.anchor-tag {
    display: inline-block;
    padding: var(--spacing-1) var(--spacing-2);
    background: var(--gray-100);
    color: var(--gray-700);
    border-radius: var(--radius-md);
    font-size: var(--font-size-xs);
    margin: 2px;
}

.anchor-tag.primary {
    background: var(--primary-100);
    color: var(--primary-700);
}

.anchor-tag.secondary {
    background: var(--gray-200);
}

.checklist-category {
    margin-bottom: var(--spacing-4);
}

.checklist-category h4 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: var(--spacing-2);
}

.checklist-items {
    list-style: none;
}

.checklist-items li {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) 0;
    font-size: var(--font-size-sm);
}

.check-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.check-icon svg {
    width: 16px;
    height: 16px;
}

.checklist-items li.checked .check-icon {
    color: var(--success-500);
}

.checklist-items li.unchecked .check-icon {
    color: var(--error-500);
}

.check-text {
    flex: 1;
    color: var(--gray-700);
}

.check-value {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    background: var(--gray-100);
    padding: 2px 8px;
    border-radius: var(--radius-sm);
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
    .seo-layout {
        grid-template-columns: 1fr;
    }

    .seo-form-panel {
        position: static;
    }

    .anchors-grid {
        grid-template-columns: 1fr;
    }
}
</style>
