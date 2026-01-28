<!-- Settings Page -->
<div class="settings-layout">
    <div class="settings-sidebar">
        <nav class="settings-nav">
            <a href="#general" class="settings-nav-item active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Général
            </a>
            <a href="#defaults" class="settings-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Paramètres par défaut
            </a>
            <a href="#api" class="settings-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                API & Intégrations
            </a>
        </nav>
    </div>

    <div class="settings-content">
        <form action="/settings" method="POST">
            <?= csrf_field() ?>

            <!-- General Settings -->
            <section id="general" class="settings-section">
                <h2>Paramètres généraux</h2>
                <p class="section-desc">Configurez les informations de base de votre compte.</p>

                <div class="form-group">
                    <label for="company_name" class="form-label">Nom de l'entreprise / Agence</label>
                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        class="form-input"
                        value="<?= e($settings['company_name'] ?? '') ?>"
                        placeholder="Votre agence SEO"
                    >
                </div>

                <div class="form-group">
                    <label for="language" class="form-label">Langue</label>
                    <select id="language" name="language" class="form-select">
                        <option value="fr" <?= ($settings['language'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
                        <option value="en" <?= ($settings['language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="es" <?= ($settings['language'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                        <option value="de" <?= ($settings['language'] ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
                    </select>
                </div>
            </section>

            <!-- Default Settings -->
            <section id="defaults" class="settings-section">
                <h2>Paramètres par défaut</h2>
                <p class="section-desc">Définissez les valeurs par défaut pour la génération de contenu.</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="default_tone" class="form-label">Ton par défaut</label>
                        <select id="default_tone" name="default_tone" class="form-select">
                            <option value="expert" <?= ($settings['default_tone'] ?? 'expert') === 'expert' ? 'selected' : '' ?>>Expert & Professionnel</option>
                            <option value="friendly" <?= ($settings['default_tone'] ?? '') === 'friendly' ? 'selected' : '' ?>>Amical & Accessible</option>
                            <option value="formal" <?= ($settings['default_tone'] ?? '') === 'formal' ? 'selected' : '' ?>>Formel & Institutionnel</option>
                            <option value="persuasive" <?= ($settings['default_tone'] ?? '') === 'persuasive' ? 'selected' : '' ?>>Persuasif & Commercial</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="default_word_count" class="form-label">Longueur par défaut</label>
                        <select id="default_word_count" name="default_word_count" class="form-select">
                            <option value="800" <?= ($settings['default_word_count'] ?? 1500) == 800 ? 'selected' : '' ?>>Court (~800 mots)</option>
                            <option value="1500" <?= ($settings['default_word_count'] ?? 1500) == 1500 ? 'selected' : '' ?>>Standard (~1500 mots)</option>
                            <option value="2500" <?= ($settings['default_word_count'] ?? 1500) == 2500 ? 'selected' : '' ?>>Long (~2500 mots)</option>
                            <option value="4000" <?= ($settings['default_word_count'] ?? 1500) == 4000 ? 'selected' : '' ?>>Très long (~4000 mots)</option>
                        </select>
                    </div>
                </div>

                <div class="toggle-group">
                    <label class="toggle-option">
                        <input type="checkbox" name="include_serp_by_default" value="1" <?= ($settings['include_serp_by_default'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Analyse SERP par défaut</strong>
                            <p>Inclure automatiquement l'analyse SERP lors de la génération</p>
                        </div>
                    </label>

                    <label class="toggle-option">
                        <input type="checkbox" name="include_semantic_by_default" value="1" <?= ($settings['include_semantic_by_default'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Analyse sémantique par défaut</strong>
                            <p>Inclure automatiquement l'analyse sémantique</p>
                        </div>
                    </label>
                </div>
            </section>

            <!-- API Settings -->
            <section id="api" class="settings-section">
                <h2>API & Intégrations</h2>
                <p class="section-desc">Configurez vos clés API pour les services externes.</p>

                <div class="form-group">
                    <label for="api_provider" class="form-label">Fournisseur IA principal</label>
                    <select id="api_provider" name="api_provider" class="form-select">
                        <option value="openai" <?= ($settings['api_provider'] ?? 'openai') === 'openai' ? 'selected' : '' ?>>OpenAI (GPT-4)</option>
                        <option value="anthropic" <?= ($settings['api_provider'] ?? '') === 'anthropic' ? 'selected' : '' ?>>Anthropic (Claude)</option>
                    </select>
                    <p class="form-help">Les clés API doivent être configurées dans le fichier .env</p>
                </div>

                <div class="api-status">
                    <div class="api-item">
                        <span class="api-name">OpenAI API</span>
                        <span class="api-badge <?= !empty($_ENV['OPENAI_API_KEY'] ?? '') ? 'connected' : 'disconnected' ?>">
                            <?= !empty($_ENV['OPENAI_API_KEY'] ?? '') ? 'Connecté' : 'Non configuré' ?>
                        </span>
                    </div>
                    <div class="api-item">
                        <span class="api-name">SERP API</span>
                        <span class="api-badge <?= !empty($_ENV['SERP_API_KEY'] ?? '') ? 'connected' : 'disconnected' ?>">
                            <?= !empty($_ENV['SERP_API_KEY'] ?? '') ? 'Connecté' : 'Non configuré' ?>
                        </span>
                    </div>
                    <div class="api-item">
                        <span class="api-name">Image API (DALL-E)</span>
                        <span class="api-badge <?= !empty($_ENV['IMAGE_API_KEY'] ?? '') ? 'connected' : 'disconnected' ?>">
                            <?= !empty($_ENV['IMAGE_API_KEY'] ?? '') ? 'Connecté' : 'Non configuré' ?>
                        </span>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.settings-layout {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: var(--spacing-6);
}

.settings-sidebar {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-4);
    box-shadow: var(--shadow-sm);
    height: fit-content;
    position: sticky;
    top: calc(var(--header-height) + var(--spacing-6));
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-1);
}

.settings-nav-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    padding: var(--spacing-3);
    border-radius: var(--radius-lg);
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: var(--gray-600);
    transition: all var(--transition-fast);
}

.settings-nav-item:hover {
    background: var(--gray-100);
    color: var(--gray-900);
}

.settings-nav-item.active {
    background: var(--primary-50);
    color: var(--primary-700);
}

.settings-nav-item svg {
    width: 18px;
    height: 18px;
}

.settings-content {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-sm);
}

.settings-section {
    padding-bottom: var(--spacing-6);
    margin-bottom: var(--spacing-6);
    border-bottom: 1px solid var(--gray-200);
}

.settings-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.settings-section h2 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--spacing-1);
}

.section-desc {
    font-size: var(--font-size-sm);
    color: var(--gray-500);
    margin-bottom: var(--spacing-5);
}

.form-help {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    margin-top: var(--spacing-2);
}

.toggle-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    margin-top: var(--spacing-4);
}

.api-status {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
    margin-top: var(--spacing-4);
    padding: var(--spacing-4);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
}

.api-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-2) 0;
}

.api-name {
    font-size: var(--font-size-sm);
    color: var(--gray-700);
}

.api-badge {
    font-size: var(--font-size-xs);
    font-weight: 500;
    padding: var(--spacing-1) var(--spacing-2);
    border-radius: var(--radius-full);
}

.api-badge.connected {
    background: #DCFCE7;
    color: #15803D;
}

.api-badge.disconnected {
    background: var(--gray-100);
    color: var(--gray-500);
}

@media (max-width: 768px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }

    .settings-sidebar {
        position: static;
    }
}
</style>
