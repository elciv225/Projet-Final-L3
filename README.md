# Projet Final L3 - Framework PHP MVC

## Description
Ce projet est un mini-framework PHP MVC (Modèle-Vue-Contrôleur) développé comme projet final de L3. Il implémente une architecture simple mais fonctionnelle permettant de créer des applications web en PHP.

## Structure du projet

```
project/
├── app/                    # Code spécifique à l'application
│   ├── Controllers/        # Contrôleurs de l'application
│   └── Models/             # Modèles de données
├── public/                 # Point d'entrée public
│   └── index.php           # Point d'entrée de l'application
├── routes/                 # Configuration des routes
│   └── web.php             # Définition des routes web
├── src/                    # Code source du framework
│   ├── Database/           # Gestion de la base de données
│   ├── Http/               # Gestion des requêtes et réponses HTTP
│   └── View/               # Système de templates
├── vendor/                 # Dépendances (géré par Composer)
├── views/                  # Fichiers de vues
│   ├── components/         # Composants réutilisables
│   └── errors/             # Pages d'erreur
├── .env                    # Variables d'environnement
└── composer.json           # Configuration des dépendances
└── Dockerfile              # Configuration Docker
└── docker-compose.yml              # Configuration Docker
```

## Fonctionnalités

- Architecture MVC
- Système de routage avec FastRoute
- Gestion des requêtes et réponses HTTP
- Système de templates simple
- Connexion à la base de données MySQL via PDO
- Gestion des erreurs 404

## Configuration

### Base de données
La configuration de la base de données s'effectue via un fichier `.env` à la racine du projet :

```
DB_HOST=mysql
DB_PORT=3306
DB_NAME=parrainage
DB_USER=miage
DB_PASSWORD=JI25
```

### Routage
Les routes sont définies dans le fichier `routes/web.php` au format suivant :

```php
return [
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/utilisateur/{id:\d+}', [UtilisateurController::class, 'show']]
];
```

## Composants principaux

### Système HTTP (src/Http/)
- `Request.php` : Encapsule les données de requête HTTP
- `Response.php` : Gère les réponses HTTP et le rendu des vues
- `Kernel.php` : Traite les requêtes et les dirige vers le bon contrôleur

### Base de données (src/Database/)
- `Database.php` : Singleton gérant la connexion à la base de données MySQL via PDO

### Vues (src/View/)
- `View.php` : Système de template simple avec inclusion de composants

## Contrôleurs disponibles
- `AccueilController` : Gère la page d'accueil
- `UtilisateurController` : Gère l'affichage des profils utilisateurs

## Dépendances
- `vlucas/phpdotenv` : Gestion des variables d'environnement
- `nikic/fast-route` : Système de routage
- `symfony/var-dumper` : Outils de débogage

## Installation

1. Cloner le dépôt
2. Exécuter `composer install` pour installer les dépendances
3. Configurer le fichier `.env` avec les informations de votre base de données
4. Configurer un serveur web pour pointer vers le dossier `public/`

## Utilisation

### Créer un nouveau contrôleur
1. Créer une classe dans `app/Controllers/`
2. Implémenter les méthodes nécessaires qui retournent un objet `Response`
3. Ajouter les routes correspondantes dans `routes/web.php`

### Créer une nouvelle vue
1. Créer un fichier PHP dans le dossier `views/`
2. Utiliser la méthode `Response::view()` dans le contrôleur pour afficher la vue

### Exemple de contrôleur
```php
<?php
namespace App\Controllers;

use System\Http\Response;

class ExempleController
{
    public function index(): Response
    {
        return Response::view('exemple', [
            'title' => 'Ma page exemple',
            'content' => 'Contenu de ma page'
        ]);
    }
}
```

### Exemple de route
```php
['GET', '/exemple', [ExempleController::class, 'index']]
```