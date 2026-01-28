<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Contrôleur d'export de contenus
 */
class ExportController extends Controller
{
    /**
     * Export au format WordPress
     */
    public function wordpress(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $content = $this->input('content', '');
        $title = $this->input('title', 'Article');
        $meta = $this->input('meta', []);

        if (empty($content)) {
            $this->json(['error' => 'Contenu requis'], 400);
        }

        // Conversion Markdown vers HTML
        $htmlContent = $this->markdownToHtml($content);

        // Structure WordPress XML
        $xml = $this->generateWordPressXML($title, $htmlContent, $meta);

        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . slugify($title) . '-wordpress.xml"');
        echo $xml;
        exit;
    }

    /**
     * Export au format HTML
     */
    public function html(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $content = $this->input('content', '');
        $title = $this->input('title', 'Article');
        $meta = $this->input('meta', []);

        if (empty($content)) {
            $this->json(['error' => 'Contenu requis'], 400);
        }

        $htmlContent = $this->markdownToHtml($content);
        $fullHtml = $this->generateFullHTML($title, $htmlContent, $meta);

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . slugify($title) . '.html"');
        echo $fullHtml;
        exit;
    }

    /**
     * Export au format Markdown
     */
    public function markdown(): void
    {
        if (!$this->isPost()) {
            $this->json(['error' => 'Méthode non autorisée'], 405);
        }

        $content = $this->input('content', '');
        $title = $this->input('title', 'Article');

        if (empty($content)) {
            $this->json(['error' => 'Contenu requis'], 400);
        }

        // Ajout des métadonnées YAML front matter
        $markdown = "---\n";
        $markdown .= "title: \"{$title}\"\n";
        $markdown .= "date: " . date('Y-m-d') . "\n";
        $markdown .= "---\n\n";
        $markdown .= $content;

        header('Content-Type: text/markdown');
        header('Content-Disposition: attachment; filename="' . slugify($title) . '.md"');
        echo $markdown;
        exit;
    }

    /**
     * Convertit le Markdown en HTML basique
     */
    private function markdownToHtml(string $markdown): string
    {
        // Titres
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $markdown);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);

        // Gras et italique
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);

        // Listes
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.+<\/li>\n?)+/', '<ul>$0</ul>', $html);

        // Liens
        $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $html);

        // Paragraphes
        $html = preg_replace('/\n\n/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';

        // Nettoyage
        $html = preg_replace('/<p>\s*<(h[1-6]|ul|ol)/', '<$1', $html);
        $html = preg_replace('/<\/(h[1-6]|ul|ol)>\s*<\/p>/', '</$1>', $html);

        return $html;
    }

    /**
     * Génère le XML WordPress
     */
    private function generateWordPressXML(string $title, string $content, array $meta): string
    {
        $slug = slugify($title);
        $date = date('Y-m-d H:i:s');
        $metaTitle = $meta['title'] ?? $title;
        $metaDesc = $meta['description'] ?? '';

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
    <title>SEO Agency Export</title>
    <link>https://example.com</link>
    <description>Export depuis SEO Agency Pro</description>
    <pubDate>{$date}</pubDate>
    <language>fr-FR</language>
    <wp:wxr_version>1.2</wp:wxr_version>

    <item>
        <title><![CDATA[{$title}]]></title>
        <link>https://example.com/{$slug}</link>
        <pubDate>{$date}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <content:encoded><![CDATA[{$content}]]></content:encoded>
        <excerpt:encoded><![CDATA[{$metaDesc}]]></excerpt:encoded>
        <wp:post_name><![CDATA[{$slug}]]></wp:post_name>
        <wp:status>draft</wp:status>
        <wp:post_type>post</wp:post_type>
        <wp:postmeta>
            <wp:meta_key>_yoast_wpseo_title</wp:meta_key>
            <wp:meta_value><![CDATA[{$metaTitle}]]></wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_yoast_wpseo_metadesc</wp:meta_key>
            <wp:meta_value><![CDATA[{$metaDesc}]]></wp:meta_value>
        </wp:postmeta>
    </item>
</channel>
</rss>
XML;
    }

    /**
     * Génère une page HTML complète
     */
    private function generateFullHTML(string $title, string $content, array $meta): string
    {
        $metaTitle = e($meta['title'] ?? $title);
        $metaDesc = e($meta['description'] ?? '');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$metaTitle}</title>
    <meta name="description" content="{$metaDesc}">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 2rem; color: #333; }
        h1 { color: #1a1a1a; border-bottom: 2px solid #6366f1; padding-bottom: 0.5rem; }
        h2 { color: #374151; margin-top: 2rem; }
        h3 { color: #4b5563; }
        p { margin: 1rem 0; }
        ul, ol { margin: 1rem 0; padding-left: 2rem; }
        a { color: #6366f1; }
        strong { color: #1f2937; }
    </style>
</head>
<body>
    <article>
        {$content}
    </article>
    <footer style="margin-top: 3rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; font-size: 0.875rem; color: #6b7280;">
        Généré par SEO Agency Pro - <?= date('d/m/Y') ?>
    </footer>
</body>
</html>
HTML;
    }
}
