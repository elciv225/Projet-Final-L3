<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Utilisateur' ?></title>
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
        <h1><?= $heading ?? 'Profil utilisateur' ?></h1>
        <nav>
            <a href="/">Accueil</a> |
            <a href="/utilisateur/1">Utilisateur</a>
        </nav>
    </header>

    <main>
        <div class="user-profile">
            <h2>Utilisateur #<?= $id ?? 2?></h2>
            <p><?= $content ?? '' ?></p>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Mon application</p>
    </footer>
</div>
</body>
</html>