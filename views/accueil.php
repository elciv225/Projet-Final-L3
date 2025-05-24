<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mon application' ?></title>
    <style>
        body {
            font-family: "Poor Richard", Serif;
            line-height: 1.6;
            margin: 10px;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<nav class="main-menu">
    <ul>
        <li>
            <a href="/" class="ajax-menu <?php echo ($_SERVER['REQUEST_URI'] === '/') ? 'active' : ''; ?>"
               data-layout="menu">Accueil</a>
        </li>
        <li>
            <a href="/utilisateur/1" class="ajax-menu <?php echo (strpos($_SERVER['REQUEST_URI'], '/utilisateur/') === 0) ? 'active' : ''; ?>"
               data-layout="menu">Profil Utilisateur</a>
        </li>
        <li>
            <a href="/produits" class="ajax-menu <?php echo ($_SERVER['REQUEST_URI'] === '/produits') ? 'active' : ''; ?>"
               data-layout="menu">Produits</a>
        </li>
        <li>
            <a href="/contact" class="ajax-menu <?php echo ($_SERVER['REQUEST_URI'] === '/contact') ? 'active' : ''; ?>"
               data-layout="menu">Contact</a>
        </li>
    </ul>
</nav>
<div class="container">
    <header>
        <h1><?= $heading ?? 'Bienvenue' ?></h1>
        <nav>
            <a href="/">Accueil</a> |
            <a href="/utilisateur/1">Utilisateur</a> |
            <a href="/authentification">Authentification</a> |
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