<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <!-- Dans votre en-tête HTML -->
    <link rel="stylesheet" href="/assets/css/ajax.css">
    <script src="/assets/js/ajax.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: auto;
        }

        input[type="text"], input[type="password"] {
            width: auto;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

    </style>
</head>
<body>
<form action="/login" method="post" class="ajax-form">
    <h2>Connexion</h2>
    <input type="text" name="login" placeholder="Login" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

</body>
</html>
