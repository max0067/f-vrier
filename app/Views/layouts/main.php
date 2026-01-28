<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Générateur de contenu IA optimisé SEO - Application SaaS pour agences">
    <title><?= e($pageTitle ?? 'SEO Content Generator') ?> - SEO Agency Pro</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">
</head>
<body class="bg-gray-50">
    <div class="app-wrapper">
        <!-- Sidebar -->
        <?php require APP_PATH . '/Views/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php require APP_PATH . '/Views/partials/header.php'; ?>

            <!-- Page Content -->
            <div class="page-content">
                <?php if (isset($flash)): ?>
                    <div class="alert alert-<?= e($flash['type']) ?>" id="flash-message">
                        <?= e($flash['message']) ?>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
