<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/authentification.css">
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <link rel="icon" href="data:,">
    <script src="/assets/js/ajax.js" defer></script>
</head>
<body id="content-area" >
<form action="/authentification"
      method="post"
      class="ajax-form"
      data-warning="Tu es sûr de vouloir soutenir ?">
    <h2>Authentification</h2>

    <?php if ($etape === 'verification'): ?>
        <input type="text" name="ip" placeholder="Identifiant Permanent" required>
        <input type="hidden" name="etape" value="verification">
    <?php elseif ($etape === 'envoi_email'): ?>
        <input type="text" name="ip" value="<?= $ip ?>" disabled>
        <input type="email" name="email" placeholder="Votre email" required>
        <input type="hidden" name="etape" value="envoi_email">
    <?php elseif ($etape === 'verification_code'): ?>
        <input type="text" name="ip" value="<?= $ip ?>" disabled>
        <input type="text" name="email" value="<?= $email ?>" disabled>
        <input type="text" name="code" placeholder="Code de vérification" required>
        <input type="hidden" name="etape" value="verification_code">
    <?php elseif ($etape === 'enregistrement'): ?>
        <input type="text" name="ip" value="<?= $ip ?>" disabled>
        <input type="text" name="email" value="<?= $email ?>" disabled>
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="password_confirm" placeholder="Confirmez le mot de passe" required>
        <input type="hidden" name="etape" value="enregistrement">
    <?php endif; ?>

    <button type="submit"><?= $txtButton ?></button>

</form>
</body>
</html>