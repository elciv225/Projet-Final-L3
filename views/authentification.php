<?php
// Récupération des variables de session
if (isset($_SESSION['auth_etudiant'])) {
    extract($_SESSION['auth_etudiant']); // Crée $nouvelleConnexion, $messageEnvoye, etc.
} else {
    $nouvelleConnexion = null;
    $messageEnvoye = null;
    $etapeAuthentification = 'verification';
    $txtButton = 'Vérifier le statut';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <!-- Dans votre en-tête HTML -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de connexion pour accéder à votre compte.">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="stylesheet" href="/assets/css/authentification.css">
    <script src="/assets/js/ajax.js" defer></script>
</head>
<body>
<form action="/authentification" method="post" class="ajax-form">
    <h2>Veuillez entrer votre Identifiant Permanent</h2>
    <input value type="text" name="ip" placeholder="Identifiant Permanent" value="$ip" required >
    <?php if ($nouvelleConnexion): ?>
        <input type="email" name="email" placeholder="Adresse e-mail" required>
        <?php if ($messageEnvoye): ?>
            <p>Un code de vérification a été envoyé à votre adresse e-mail. Veuillez le saisir ci-dessous.</p>
            <input type="text" name="code_verification" placeholder="Code de vérification" required>
            <input type="password" name="password" placeholder="Mot de passe" required value="">
            <input type="password" name="password_confirm" placeholder="Confirmer le mot de passe" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
        <?php endif; ?>
    <?php endif; ?>
    <input type="text" name="<?= $etapeAuthentification ?>" value="<?= htmlspecialchars($etapeAuthentification) ?>">
    <button type="submit"><?= htmlspecialchars($txtButton) ?></button>
</form>

</body>
</html>
