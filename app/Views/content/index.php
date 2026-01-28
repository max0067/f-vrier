<!-- Content Generator Form -->
<div class="generator-wrapper">
    <div class="generator-main">
        <form id="content-generator-form" class="generator-form" action="/content/generate" method="POST">
            <?= csrf_field() ?>

            <!-- Main Prompt -->
            <div class="form-section">
                <label for="prompt" class="form-label">
                    Sujet / Mot-clé principal
                    <span class="required">*</span>
                </label>
                <div class="prompt-input-wrapper">
                    <textarea
                        id="prompt"
                        name="prompt"
                        class="form-textarea prompt-textarea"
                        placeholder="Ex: Comment choisir son assurance habitation en 2024 ? Conseils d'expert pour trouver la meilleure offre..."
                        rows="3"
                        required
                    ><?= e($_POST['prompt'] ?? '') ?></textarea>
                    <div class="prompt-suggestions">
                        <span class="suggestion-label">Suggestions :</span>
                        <button type="button" class="suggestion-tag" data-prompt="Guide complet">Guide complet</button>
                        <button type="button" class="suggestion-tag" data-prompt="Comparatif">Comparatif</button>
                        <button type="button" class="suggestion-tag" data-prompt="Tutoriel étape par étape">Tutoriel</button>
                        <button type="button" class="suggestion-tag" data-prompt="Avis d'expert">Avis expert</button>
                    </div>
                </div>
            </div>

            <!-- Content Options -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="type" class="form-label">Type de contenu</label>
                    <select id="type" name="type" class="form-select">
                        <option value="article">Article de blog</option>
                        <option value="guide">Guide complet</option>
                        <option value="comparatif">Comparatif</option>
                        <option value="tutoriel">Tutoriel</option>
                        <option value="landing">Page de vente</option>
                        <option value="product">Fiche produit</option>
                        <option value="local">Page locale SEO</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tone" class="form-label">Ton éditorial</label>
                    <select id="tone" name="tone" class="form-select">
                        <option value="expert">Expert & Professionnel</option>
                        <option value="friendly">Amical & Accessible</option>
                        <option value="formal">Formel & Institutionnel</option>
                        <option value="persuasive">Persuasif & Commercial</option>
                        <option value="educational">Pédagogique</option>
                        <option value="journalistic">Journalistique</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="word_count" class="form-label">Nombre de mots</label>
                    <select id="word_count" name="word_count" class="form-select">
                        <option value="800">Court (~800 mots)</option>
                        <option value="1500" selected>Standard (~1500 mots)</option>
                        <option value="2500">Long (~2500 mots)</option>
                        <option value="4000">Très long (~4000 mots)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="versions" class="form-label">Versions à générer</label>
                    <select id="versions" name="versions" class="form-select">
                        <option value="1">1 version</option>
                        <option value="2">2 versions</option>
                        <option value="3">3 versions</option>
                    </select>
                </div>
            </div>

            <!-- EEAT Options -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Optimisation EEAT
                </h3>
                <div class="checkbox-grid">
                    <label class="checkbox-card">
                        <input type="checkbox" name="eeat_expertise" checked>
                        <div class="checkbox-content">
                            <span class="checkbox-icon">E</span>
                            <div>
                                <strong>Expertise</strong>
                                <p>Inclure des données techniques et expertise métier</p>
                            </div>
                        </div>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="eeat_experience" checked>
                        <div class="checkbox-content">
                            <span class="checkbox-icon">E</span>
                            <div>
                                <strong>Expérience</strong>
                                <p>Ajouter des témoignages et retours d'expérience</p>
                            </div>
                        </div>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="eeat_authority" checked>
                        <div class="checkbox-content">
                            <span class="checkbox-icon">A</span>
                            <div>
                                <strong>Autorité</strong>
                                <p>Citer des sources et références fiables</p>
                            </div>
                        </div>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="eeat_trust" checked>
                        <div class="checkbox-content">
                            <span class="checkbox-icon">T</span>
                            <div>
                                <strong>Fiabilité</strong>
                                <p>Garantir la transparence et la véracité</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Analysis Options -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Analyses complémentaires
                </h3>
                <div class="toggle-options">
                    <label class="toggle-option">
                        <input type="checkbox" name="include_serp" value="1" checked>
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Analyse SERP</strong>
                            <p>Analyser les 10 premiers résultats Google</p>
                        </div>
                    </label>
                    <label class="toggle-option">
                        <input type="checkbox" name="include_semantic" value="1" checked>
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Analyse sémantique</strong>
                            <p>Identifier les mots-clés et champs lexicaux</p>
                        </div>
                    </label>
                    <label class="toggle-option">
                        <input type="checkbox" name="include_image" value="1">
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Générer une image</strong>
                            <p>Créer une image à la une avec l'IA</p>
                        </div>
                    </label>
                    <label class="toggle-option">
                        <input type="checkbox" name="include_meta" value="1" checked>
                        <span class="toggle-slider"></span>
                        <div class="toggle-content">
                            <strong>Méta SEO</strong>
                            <p>Générer titre et meta description</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg" id="generate-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                    Générer le contenu
                </button>
                <button type="reset" class="btn btn-ghost">Réinitialiser</button>
            </div>
        </form>
    </div>

    <!-- Results Panel -->
    <div class="generator-results" id="results-panel">
        <div class="results-header">
            <h2>Résultats</h2>
            <div class="results-actions">
                <button class="btn btn-ghost btn-sm" id="copy-btn" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    Copier
                </button>
                <button class="btn btn-ghost btn-sm" id="export-btn" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Exporter
                </button>
            </div>
        </div>

        <div class="results-content" id="results-content">
            <div class="results-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <h3>Prêt à générer</h3>
                <p>Remplissez le formulaire et cliquez sur "Générer" pour créer votre contenu optimisé SEO.</p>
            </div>
        </div>

        <!-- Loading State -->
        <div class="results-loading" id="results-loading" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text">
                <h3>Génération en cours...</h3>
                <p id="loading-status">Analyse du sujet</p>
            </div>
            <div class="loading-progress">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['generated_content'])): ?>
<script>
    window.generatedContent = <?= json_encode($_SESSION['generated_content']) ?>;
</script>
<?php unset($_SESSION['generated_content']); endif; ?>
