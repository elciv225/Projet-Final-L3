<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mon application' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1><?= $heading ?? 'Bienvenue' ?></h1>
        <nav>
            <a href="/">Accueil</a> |
            <a href="/utilisateur/1">Utilisateur</a> |
            <a href="/page-inexistante">Page inexistante (test 404)</a>
        </nav>
    </header>

    <main>
        <p><?= $content ?? '' ?></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Mon application</p>
    </footer>
</div>
</body>
</html>