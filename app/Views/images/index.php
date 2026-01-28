<!-- Image Generator -->
<div class="generator-layout">
    <div class="generator-panel">
        <form id="image-form" action="/images/generate" method="POST">
            <?= csrf_field() ?>

            <div class="form-section">
                <label for="subject" class="form-label">
                    Description de l'image
                    <span class="required">*</span>
                </label>
                <textarea
                    id="subject"
                    name="subject"
                    class="form-textarea"
                    placeholder="Décrivez l'image souhaitée de manière détaillée. Ex: Un bureau moderne avec un ordinateur portable, une tasse de café, une plante verte, lumière naturelle douce..."
                    rows="4"
                    required
                ></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="style" class="form-label">Style photographique</label>
                    <select id="style" name="style" class="form-select">
                        <option value="photorealistic">Photo-réaliste</option>
                        <option value="editorial">Éditorial / Magazine</option>
                        <option value="lifestyle">Lifestyle</option>
                        <option value="corporate">Corporate / Business</option>
                        <option value="artistic">Artistique</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="size" class="form-label">Format</label>
                    <select id="size" name="size" class="form-select">
                        <option value="1792x1024">Paysage (16:9)</option>
                        <option value="1024x1024">Carré (1:1)</option>
                        <option value="1024x1792">Portrait (9:16)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    Générer l'image
                </button>
            </div>
        </form>
    </div>

    <div class="preview-panel">
        <?php if (isset($generatedImage)): ?>
            <div class="image-result">
                <div class="image-preview">
                    <img src="<?= e($generatedImage['image_url']) ?>" alt="Image générée">
                </div>
                <div class="image-details">
                    <h3>Détails SEO de l'image</h3>
                    <div class="detail-row">
                        <span class="detail-label">Nom de fichier :</span>
                        <span class="detail-value copyable"><?= e($generatedImage['seo']['filename']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Texte alternatif :</span>
                        <span class="detail-value copyable"><?= e($generatedImage['seo']['alt_text']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Légende :</span>
                        <span class="detail-value copyable"><?= e($generatedImage['seo']['caption']) ?></span>
                    </div>
                </div>
                <div class="image-actions">
                    <a href="<?= e($generatedImage['image_url']) ?>" download class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Télécharger
                    </a>
                    <button class="btn btn-ghost" onclick="SEOApp.utils.copyToClipboard('<?= e($generatedImage['image_url']) ?>')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copier l'URL
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="preview-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                <h3>Aperçu de l'image</h3>
                <p>L'image générée apparaîtra ici avec ses attributs SEO optimisés.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.generator-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-6);
    align-items: start;
}

.generator-panel {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
}

.preview-panel {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
    min-height: 400px;
}

.preview-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    min-height: 350px;
    text-align: center;
    color: var(--gray-400);
}

.preview-placeholder svg {
    width: 64px;
    height: 64px;
    margin-bottom: var(--spacing-4);
}

.preview-placeholder h3 {
    font-size: var(--font-size-lg);
    color: var(--gray-600);
    margin-bottom: var(--spacing-2);
}

.image-result {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
}

.image-preview {
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--gray-100);
}

.image-preview img {
    width: 100%;
    height: auto;
    display: block;
}

.image-details {
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    padding: var(--spacing-4);
}

.image-details h3 {
    font-size: var(--font-size-sm);
    font-weight: 600;
    margin-bottom: var(--spacing-3);
}

.detail-row {
    display: flex;
    gap: var(--spacing-2);
    margin-bottom: var(--spacing-2);
    font-size: var(--font-size-sm);
}

.detail-label {
    color: var(--gray-500);
    min-width: 120px;
}

.detail-value {
    color: var(--gray-900);
    cursor: pointer;
}

.detail-value:hover {
    color: var(--primary-600);
}

.image-actions {
    display: flex;
    gap: var(--spacing-3);
}

.copyable {
    cursor: pointer;
    transition: color 0.2s;
}

.copyable:hover {
    color: var(--primary-600);
}

@media (max-width: 1024px) {
    .generator-layout {
        grid-template-columns: 1fr;
    }
}
</style>
