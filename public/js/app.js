/**
 * SEO Agency Pro - Application JavaScript
 * Gestion des interactions et appels AJAX
 */

(function() {
    'use strict';

    // ========================================
    // Configuration
    // ========================================
    const API_BASE = '';
    const CSRF_TOKEN = document.querySelector('input[name="_token"]')?.value || '';

    // ========================================
    // Utilitaires
    // ========================================
    const utils = {
        /**
         * Requête AJAX
         */
        async fetch(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            };

            if (options.body && typeof options.body === 'object') {
                options.body._token = CSRF_TOKEN;
                options.body = JSON.stringify(options.body);
            }

            const response = await fetch(API_BASE + url, {
                ...defaultOptions,
                ...options,
            });

            if (!response.ok) {
                const error = await response.json().catch(() => ({ error: 'Erreur serveur' }));
                throw new Error(error.error || 'Erreur inconnue');
            }

            return response.json();
        },

        /**
         * Affiche une notification
         */
        notify(message, type = 'info') {
            const container = document.querySelector('.page-content');
            if (!container) return;

            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                ${message}
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            `;

            container.insertBefore(alert, container.firstChild);

            setTimeout(() => alert.remove(), 5000);
        },

        /**
         * Copie dans le presse-papier
         */
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.notify('Copié dans le presse-papier', 'success');
            } catch (err) {
                // Fallback pour les anciens navigateurs
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.notify('Copié dans le presse-papier', 'success');
            }
        },

        /**
         * Formate du Markdown basique en HTML
         */
        markdownToHtml(markdown) {
            let html = markdown
                // Titres
                .replace(/^### (.+)$/gm, '<h3>$1</h3>')
                .replace(/^## (.+)$/gm, '<h2>$1</h2>')
                .replace(/^# (.+)$/gm, '<h1>$1</h1>')
                // Gras et italique
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                // Listes
                .replace(/^- (.+)$/gm, '<li>$1</li>')
                // Liens
                .replace(/\[(.+?)\]\((.+?)\)/g, '<a href="$2" target="_blank">$1</a>')
                // Citations
                .replace(/^> (.+)$/gm, '<blockquote>$1</blockquote>')
                // Paragraphes
                .replace(/\n\n/g, '</p><p>');

            return '<p>' + html + '</p>';
        },

        /**
         * Compte les mots
         */
        wordCount(text) {
            return text.trim().split(/\s+/).filter(word => word.length > 0).length;
        },

        /**
         * Estime le temps de lecture
         */
        readingTime(text, wordsPerMinute = 200) {
            const words = this.wordCount(text);
            return Math.ceil(words / wordsPerMinute);
        }
    };

    // ========================================
    // Sidebar Toggle (Mobile)
    // ========================================
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Fermer au clic à l'extérieur
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // ========================================
    // User Dropdown Menu
    // ========================================
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        document.addEventListener('click', () => {
            userDropdown.classList.remove('show');
        });
    }

    // ========================================
    // Content Generator
    // ========================================
    const contentForm = document.getElementById('content-generator-form');
    const resultsPanel = document.getElementById('results-panel');
    const resultsContent = document.getElementById('results-content');
    const resultsLoading = document.getElementById('results-loading');
    const loadingStatus = document.getElementById('loading-status');
    const generateBtn = document.getElementById('generate-btn');
    const copyBtn = document.getElementById('copy-btn');
    const exportBtn = document.getElementById('export-btn');

    if (contentForm) {
        contentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(contentForm);
            const data = Object.fromEntries(formData.entries());

            // Afficher le loading
            if (resultsContent) resultsContent.style.display = 'none';
            if (resultsLoading) resultsLoading.style.display = 'flex';
            if (generateBtn) generateBtn.disabled = true;

            // Statuts de progression
            const statuses = [
                'Analyse du sujet...',
                'Scraping de la SERP...',
                'Analyse sémantique...',
                'Génération du contenu...',
                'Optimisation EEAT...',
                'Finalisation...'
            ];

            let statusIndex = 0;
            const statusInterval = setInterval(() => {
                if (loadingStatus && statusIndex < statuses.length) {
                    loadingStatus.textContent = statuses[statusIndex];
                    statusIndex++;
                }
            }, 2000);

            try {
                const result = await utils.fetch('/api/content/generate', {
                    method: 'POST',
                    body: data,
                });

                clearInterval(statusInterval);

                if (result.status === 'success' && result.data) {
                    displayContentResult(result.data);
                    utils.notify('Contenu généré avec succès !', 'success');
                } else {
                    throw new Error(result.error || 'Erreur lors de la génération');
                }

            } catch (error) {
                clearInterval(statusInterval);
                utils.notify(error.message, 'error');

                if (resultsContent) {
                    resultsContent.innerHTML = `
                        <div class="results-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <h3>Erreur</h3>
                            <p>${error.message}</p>
                        </div>
                    `;
                    resultsContent.style.display = 'block';
                }
            } finally {
                if (resultsLoading) resultsLoading.style.display = 'none';
                if (generateBtn) generateBtn.disabled = false;
            }
        });
    }

    /**
     * Affiche les résultats de génération
     */
    function displayContentResult(data) {
        if (!resultsContent) return;

        const content = data.content;
        const version = content.versions[0];
        const meta = content.meta;

        let html = `
            <div class="result-tabs">
                <button class="result-tab active" data-tab="content">Contenu</button>
                <button class="result-tab" data-tab="meta">SEO</button>
                <button class="result-tab" data-tab="analysis">Analyse</button>
            </div>

            <div class="result-tab-content active" id="tab-content">
                <div class="result-stats">
                    <span class="stat"><strong>${version.word_count}</strong> mots</span>
                    <span class="stat"><strong>${version.reading_time}</strong> min de lecture</span>
                    <span class="stat">Score: <strong>${version.score.total}/100</strong></span>
                </div>
                <div class="result-article">
                    ${utils.markdownToHtml(version.content)}
                </div>
            </div>

            <div class="result-tab-content" id="tab-meta">
                <div class="meta-section">
                    <h4>Titre SEO</h4>
                    <div class="meta-value copyable" data-copy="${meta.title}">
                        ${meta.title}
                        <span class="meta-length">${meta.title.length}/60</span>
                    </div>
                </div>
                <div class="meta-section">
                    <h4>Meta Description</h4>
                    <div class="meta-value copyable" data-copy="${meta.meta_description}">
                        ${meta.meta_description}
                        <span class="meta-length">${meta.meta_description.length}/155</span>
                    </div>
                </div>
                <div class="meta-section">
                    <h4>URL suggérée</h4>
                    <div class="meta-value copyable" data-copy="${meta.canonical}">
                        ${meta.canonical}
                    </div>
                </div>
                <div class="meta-section">
                    <h4>Liens internes suggérés</h4>
                    <ul class="links-list">
                        ${content.internal_links.map(link => `
                            <li>
                                <span class="anchor">${link.anchor}</span>
                                <span class="url">${link.suggestion}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            </div>

            <div class="result-tab-content" id="tab-analysis">
                <div class="score-cards">
                    <div class="score-card">
                        <div class="score-value">${version.score.total}</div>
                        <div class="score-label">Score Global</div>
                    </div>
                    <div class="score-card">
                        <div class="score-value">${version.score.readability}</div>
                        <div class="score-label">Lisibilité</div>
                    </div>
                    <div class="score-card">
                        <div class="score-value">${version.score.seo}</div>
                        <div class="score-label">SEO</div>
                    </div>
                    <div class="score-card">
                        <div class="score-value">${version.score.eeat}</div>
                        <div class="score-label">EEAT</div>
                    </div>
                </div>
        `;

        // Ajout des données sémantiques si disponibles
        if (data.semantic) {
            html += `
                <div class="analysis-section">
                    <h4>Intention de recherche</h4>
                    <div class="intent-badge intent-${data.semantic.intent.type}">
                        ${data.semantic.intent.type}
                    </div>
                    <p class="intent-desc">${data.semantic.intent.description}</p>
                </div>
                <div class="analysis-section">
                    <h4>Mots-clés suggérés</h4>
                    <div class="keywords-list">
                        ${data.semantic.keywords.slice(0, 10).map(kw => `
                            <span class="keyword-tag">${kw}</span>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        html += '</div>';

        resultsContent.innerHTML = html;
        resultsContent.style.display = 'block';

        // Activer les boutons
        if (copyBtn) copyBtn.disabled = false;
        if (exportBtn) exportBtn.disabled = false;

        // Stocker le contenu pour copie/export
        window.generatedContent = {
            markdown: version.content,
            meta: meta,
        };

        // Gestion des onglets
        initResultTabs();

        // Gestion des éléments copiables
        initCopyables();
    }

    /**
     * Initialise les onglets de résultats
     */
    function initResultTabs() {
        const tabs = document.querySelectorAll('.result-tab');
        const contents = document.querySelectorAll('.result-tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetId = 'tab-' + tab.dataset.tab;

                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                document.getElementById(targetId)?.classList.add('active');
            });
        });
    }

    /**
     * Initialise les éléments copiables
     */
    function initCopyables() {
        document.querySelectorAll('.copyable').forEach(el => {
            el.addEventListener('click', () => {
                const text = el.dataset.copy || el.textContent;
                utils.copyToClipboard(text.trim());
            });
        });
    }

    // Bouton copier
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            if (window.generatedContent) {
                utils.copyToClipboard(window.generatedContent.markdown);
            }
        });
    }

    // Bouton exporter
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            if (!window.generatedContent) return;

            // Créer un menu d'export
            const menu = document.createElement('div');
            menu.className = 'export-menu';
            menu.innerHTML = `
                <button data-format="markdown">Markdown (.md)</button>
                <button data-format="html">HTML</button>
                <button data-format="wordpress">WordPress (XML)</button>
            `;

            menu.style.cssText = `
                position: absolute;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                padding: 8px;
                z-index: 1000;
            `;

            const rect = exportBtn.getBoundingClientRect();
            menu.style.top = rect.bottom + 'px';
            menu.style.right = (window.innerWidth - rect.right) + 'px';

            document.body.appendChild(menu);

            menu.querySelectorAll('button').forEach(btn => {
                btn.style.cssText = `
                    display: block;
                    width: 100%;
                    padding: 8px 16px;
                    border: none;
                    background: none;
                    text-align: left;
                    cursor: pointer;
                    border-radius: 4px;
                `;
                btn.addEventListener('mouseover', () => btn.style.background = '#f3f4f6');
                btn.addEventListener('mouseout', () => btn.style.background = 'none');
                btn.addEventListener('click', () => {
                    exportContent(btn.dataset.format);
                    menu.remove();
                });
            });

            // Fermer au clic à l'extérieur
            setTimeout(() => {
                document.addEventListener('click', function closeMenu(e) {
                    if (!menu.contains(e.target)) {
                        menu.remove();
                        document.removeEventListener('click', closeMenu);
                    }
                });
            }, 0);
        });
    }

    /**
     * Exporte le contenu
     */
    function exportContent(format) {
        const content = window.generatedContent;
        if (!content) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/export/${format}`;
        form.style.display = 'none';

        const inputs = {
            _token: CSRF_TOKEN,
            content: content.markdown,
            title: content.meta.title,
            'meta[title]': content.meta.title,
            'meta[description]': content.meta.meta_description,
        };

        for (const [name, value] of Object.entries(inputs)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        form.remove();
    }

    // ========================================
    // Suggestion Tags
    // ========================================
    document.querySelectorAll('.suggestion-tag').forEach(tag => {
        tag.addEventListener('click', () => {
            const prompt = document.getElementById('prompt');
            if (prompt) {
                const currentValue = prompt.value.trim();
                const suggestion = tag.dataset.prompt;
                prompt.value = currentValue ? `${currentValue} - ${suggestion}` : suggestion;
                prompt.focus();
            }
        });
    });

    // ========================================
    // Contenu pré-généré (si session)
    // ========================================
    if (window.generatedContent && resultsContent) {
        displayContentResult({ content: window.generatedContent });
    }

    // ========================================
    // Flash messages auto-dismiss
    // ========================================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // ========================================
    // Export global
    // ========================================
    window.SEOApp = {
        utils,
        notify: utils.notify.bind(utils),
    };

})();
