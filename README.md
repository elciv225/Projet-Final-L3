# Projet Final L3 - Framework PHP MVC

## Description
Ce projet est une application web développée pour la gestion du personnel (administratif, enseignants) et des étudiants d'un établissement d'enseignement. Elle s'appuie sur un mini-framework PHP MVC (Modèle-Vue-Contrôleur) développé sur mesure pour ce projet final de L3. L'application offre une interface d'administration pour gérer les différentes entités et leurs interactions au sein de l'établissement.

## Structure du projet

```
project/
├── app/                    # Code spécifique à l'application
│   ├── Controllers/        # Contrôleurs de base et d'authentification
│   │   ├── Gestions/       # Contrôleurs pour les modules de gestion (étudiants, etc.)
│   │   └── Public/         # Contrôleurs pour les pages publiques (accueil, etc.)
│   ├── Dao/                # Data Access Objects (interaction base de données)
│   └── Models/             # Modèles de données (Entités/Objets métier) - Convention, peut contenir des TODOs
├── base.sql                # Script SQL initial pour la base de données
├── etats/                  # Modèles de documents PDF/HTML (bulletins, reçus, etc.)
├── node_modules/           # Dépendances JavaScript (développement front-end)
├── public/                 # Point d'entrée public de l'application
│   ├── assets/             # Fichiers statiques (CSS, JS, images)
│   └── index.php           # Point d'entrée principal de l'application
├── routes/                 # Configuration des routes
│   └── web.php             # Définition des routes web
├── scripts/                # Scripts utiles (linting, tests, gestion d'environnement)
├── src/                    # Code source du framework de base
│   ├── Database/           # Gestion de la connexion à la base de données (PDO)
│   ├── Http/               # Gestion des requêtes et réponses HTTP (Request, Response, Kernel)
│   ├── Security/           # Composants liés à la sécurité (ex: Crypto)
│   └── View/               # Système de rendu des vues
├── vendor/                 # Dépendances PHP (gérées par Composer)
├── views/                  # Fichiers de vues (templates PHP)
│   ├── admin/              # Vues spécifiques à l'espace administrateur
│   ├── errors/             # Pages d'erreur (404, etc.)
│   ├── gestion/            # Vues pour les modules de gestion
│   └── public/             # Vues pour les pages publiques
├── .env                    # Variables d'environnement (configuration locale)
├── .gitignore              # Fichiers et dossiers ignorés par Git
├── composer.json           # Configuration des dépendances PHP (Composer)
├── package.json            # Configuration des dépendances JavaScript (npm/yarn)
├── Dockerfile              # Dockerfile principal (peut être switché, ex: Dockerfile.prod)
├── Dockerfile.dev          # Dockerfile pour l'environnement de développement
├── Dockerfile.prod         # Dockerfile pour l'environnement de production
└── docker-compose.yml      # Configuration pour Docker Compose
```

## Fonctionnalités

**Fonctionnalités du Framework de Base :**
- **Architecture MVC (Modèle-Vue-Contrôleur) :** Structure claire séparant la logique métier, la présentation et le contrôle des flux.
- **Système de Routage :** Utilisation de `nikic/fast-route` pour un routage performant et flexible. Définition des routes centralisée dans `routes/web.php`.
- **Gestion des Requêtes et Réponses HTTP :** Objets `Request` et `Response` pour encapsuler et gérer les données HTTP.
- **Système de Vues/Templates :** Mécanisme simple pour le rendu de vues PHP, permettant l'injection de données et la structuration du HTML.
- **Connexion à la Base de Données :** Utilisation de PDO pour l'interaction avec la base de données MySQL, configurée via `.env`. Classe `Database` pour la gestion de la connexion.
- **Gestion des Erreurs :** Mécanismes de base pour la gestion des erreurs (ex: page 404).
- **Autoloader PSR-4 :** Chargement automatique des classes grâce à Composer.

**Fonctionnalités Spécifiques de l'Application de Gestion :**
- **Authentification des Utilisateurs :** Système de connexion/déconnexion pour sécuriser l'accès aux différentes parties de l'application.
- **Espace Administrateur :** Interface dédiée (`/espace-administrateur`) pour la gestion centrale des modules.
- **Gestion du Personnel Administratif :** Module CRUD (Créer, Lire, Mettre à jour, Supprimer) pour le personnel administratif.
- **Gestion des Enseignants :** Module CRUD pour le corps enseignant.
- **Gestion des Étudiants :** Module CRUD pour les étudiants inscrits.
- **Génération Dynamique des Routes de Gestion :** Les routes pour les modules de gestion (personnel, enseignants, étudiants) et leurs traitements (ajout, modification, suppression) sont générées dynamiquement à partir de la configuration `\$configurationModules` dans `routes/web.php`.
- **Gestion des sessions et des messages flash** (implicite par la nature d'une application web avec authentification et formulaires, mais bon à noter si des mécanismes spécifiques existent).
- **Scripts Utilitaires :** Fourniture de scripts pour le linting du code (PHP, CSS, JS), l'exécution des tests, et la gestion des configurations d'environnement (Docker, .env).

## Configuration

La configuration principale de l'application s'effectue via des variables d'environnement définies dans un fichier `.env` à la racine du projet. Ce fichier est chargé au démarrage de l'application grâce à la dépendance `vlucas/phpdotenv`.

Il est recommandé de copier le fichier `.env.example` (s'il existe, sinon en créer un basé sur l'exemple ci-dessous) en `.env` et de l'adapter à votre environnement local.

### Variables d'environnement (`.env`)
Voici un exemple des variables d'environnement typiques pour ce projet :

```env
# Configuration de la base de données
DB_HOST=mysql_db        # Hôte de la base de données (souvent le nom du service Docker)
DB_PORT=3306            # Port de la base de données
DB_NAME=nom_de_la_base  # Nom de la base de données
DB_USER=utilisateur_bd  # Utilisateur de la base de données
DB_PASSWORD=mot_de_passe_bd # Mot de passe de l'utilisateur

# Configuration de l'application (exemples)
APP_ENV=dev             # Environnement de l'application (dev, prod)
APP_DEBUG=true          # Activer/Désactiver le mode débogage
APP_URL=http://localhost:8080 # URL de base de l'application

# Autres configurations spécifiques si nécessaires...
```
**Note:** Assurez-vous que le nom `DB_NAME`, `DB_USER`, `DB_PASSWORD` correspondent à ceux définis dans votre `base.sql` ou votre configuration de base de données. Le `DB_HOST` est généralement le nom du service de base de données défini dans `docker-compose.yml` (par exemple, `mysql_db`).

### Gestion des Environnements Multiples

Le projet inclut des scripts pour faciliter la gestion de différentes configurations, notamment pour Docker :

-   **`scripts/switch-dockerfile.sh`**: Permet de changer le `Dockerfile` utilisé par `docker-compose.yml` (par exemple, entre `Dockerfile.dev` et `Dockerfile.prod`).
-   **`scripts/switch-docker-compose.sh`**: Pourrait être utilisé pour alterner entre différents fichiers `docker-compose.yml` (si plusieurs configurations existent).
-   **`scripts/switch-env.sh`**: Pourrait servir à changer de fichier `.env` (par exemple, `.env.dev`, `.env.prod`).
-   **`scripts/switch-ignore.sh`**: Potentiellement pour modifier les patterns dans `.gitignore` selon l'environnement.

Consultez ces scripts pour comprendre leur fonctionnement exact et les adapter à vos besoins. Par exemple, pour passer à un environnement de production, vous pourriez utiliser `./scripts/switch-dockerfile.sh Dockerfile.prod`.

## Routage

Le système de routage de l'application est géré par la bibliothèque `nikic/fast-route`. Les routes sont définies dans le fichier `routes/web.php`.

### Définition des Routes Simples

Une route simple est définie par un tableau contenant la méthode HTTP, le chemin (URI), et le couple contrôleur/méthode à exécuter.

**Exemple de route simple :**

```php
// Dans routes/web.php
return [
    // ... autres routes
    ['GET', '/', [App\Controllers\Public\AccueilController::class, 'index']],
    ['GET', '/authentification', [\App\Controllers\Public\InscriptionController::class, 'index']],
    ['POST', '/authentification', [\App\Controllers\Public\InscriptionController::class, 'authentification']],
    // ...
];
```
Dans cet exemple :
-   `['GET', '/', ...]` mappe une requête GET sur la racine `/` à la méthode `index` du `AccueilController`.
-   Les routes pour `/authentification` (GET pour afficher le formulaire, POST pour le soumettre) sont également montrées.

### Génération Automatique des Routes pour les Modules de Gestion

Une caractéristique importante du système de routage de ce projet est la génération dynamique des routes pour les modules de gestion (personnel administratif, enseignants, étudiants). Ceci est configuré via le tableau `\$configurationModules` dans `routes/web.php`.

Pour chaque module défini dans ce tableau, les routes suivantes sont automatiquement créées :
-   Une route `GET` pour afficher la page principale du module (par exemple, `/espace-administrateur/gestion/etudiants`).
-   Des routes (généralement `POST`) pour les différents traitements (actions CRUD) définis pour le module (par exemple, `/espace-administrateur/gestion/etudiants/ajouter`, `/espace-administrateur/gestion/etudiants/modifier`, etc.).
-   Des routes d'API directes pour les traitements (par exemple `/gestion/etudiants/ajouter`).

**Extrait de la configuration des modules (`routes/web.php`) :**

```php
\$configurationModules = [
    'menu_views' => [
        'etudiants' => [
            'controleur' => App\Controllers\Traitements\EtudiantsController::class,
            'methodePrincipale' => 'index',
            'menu_views' => [
                'ajouter' => ['methodeHttp' => 'POST', /* ... */],
                'modifier' => ['methodeHttp' => 'POST', /* ... */],
                'supprimer' => ['methodeHttp' => 'POST', /* ... */]
            ]
        ],
        // ... autres modules (enseignants, personnel-administratif)
    ]
];
```
Ce mécanisme permet d'ajouter de nouveaux modules de gestion avec leurs opérations CRUD de manière structurée et d'avoir leurs routes générées automatiquement, réduisant la duplication de code et les erreurs potentielles. Les requêtes vers ces routes sont typiquement gérées par `AdministrateurController::gestionMenuModules()` pour l'affichage et `AdministrateurController::gererTraitementModule()` pour les actions, ou directement par le contrôleur spécifique du module pour les routes d'API.

## Composants Principaux

L'application est structurée en deux ensembles majeurs de composants : le noyau du framework (`src/`) et la logique applicative spécifique (`app/`).

### Noyau du Framework (`src/`)

Le répertoire `src/` contient les fondations du mini-framework MVC.

-   **`src/Http/` : Gestion des Requêtes et Réponses HTTP**
    -   `Request.php` : Encapsule les données de la requête HTTP entrante (comme `\$_GET`, `\$_POST`, `\$_SERVER`).
    -   `Response.php` : Gère la construction de la réponse HTTP, y compris le rendu des vues et l'envoi des en-têtes.
    -   `Kernel.php` : Cœur du traitement des requêtes. Il reçoit une requête, la fait correspondre à une route, exécute le contrôleur approprié et retourne la réponse.

-   **`src/Database/Database.php` : Gestion de la Base de Données**
    -   Fournit une instance unique (Singleton) de la connexion PDO à la base de données.
    -   Utilise les informations du fichier `.env` pour la configuration de la connexion.

-   **`src/View/View.php` : Système de Vues**
    -   Permet de rendre des fichiers de vues (templates PHP).
    -   Prend en charge l'injection de données du contrôleur vers la vue.
    -   Gère la logique d'inclusion de fragments de vue ou de layouts (si applicable).

-   **`src/Security/Crypto.php` : Utilitaires de Sécurité**
    -   Contient potentiellement des fonctions pour la gestion de la sécurité, comme le hachage de mots de passe ou d'autres opérations cryptographiques. (Le contenu exact peut nécessiter une inspection plus approfondie du fichier).

### Logique Applicative (`app/`)

Le répertoire `app/` contient le code spécifique à l'application de gestion.

-   **`app/Controllers/` : Contrôleurs**
    -   `Controller.php` (si existant) : Peut servir de contrôleur de base avec des fonctionnalités communes.
    -   `AuthentificationController.php` : Gère la logique d'authentification (connexion, déconnexion, etc.).
    -   `AdministrateurController.php` : Gère les fonctionnalités de l'espace administrateur, y compris l'affichage des menus de gestion et le traitement des actions CRUD pour les modules configurés.
    -   **`app/Controllers/Public/`** : Contient les contrôleurs pour les parties publiques du site.
        -   `AccueilController.php` : Gère la page d'accueil.
    -   **`app/Controllers/Gestions/`** : Contient les contrôleurs spécifiques pour chaque module de gestion.
        -   `EnseignantsController.php`, `EtudiantsController.php`, `PersonnelAdministratifController.php` : Chacun gère les opérations CRUD (lister, afficher, ajouter, modifier, supprimer) pour son entité respective, souvent en réponse à des appels AJAX ou des soumissions de formulaire directes (API-like).

-   **`app/Dao/DAO.php` : Data Access Object (DAO)**
    -   Classe abstraite `DAO.php` : Définit une interface commune et des implémentations de base pour l'accès aux données (CRUD : `recupererParId`, `recupererTous`, `creer`, `mettreAJour`, `supprimer`, `rechercher`, etc.).
    -   Les DAO concrets (qui hériteraient de `DAO.php`, un par entité, par exemple `EtudiantDAO.php`) encapsulent la logique d'interaction avec la base de données pour des modèles spécifiques. (Note: la présence de DAO concrets doit être vérifiée, le README décrira le pattern).

-   **`app/Models/` : Modèles de Données**
    -   Ce répertoire est destiné à contenir les classes représentant les entités métier de l'application (par exemple, `Etudiant.php`, `Enseignant.php`).
    -   Ces objets modèles sont typiquement utilisés pour transférer des données entre les DAO et les Contrôleurs, et ensuite vers les Vues.
    -   Actuellement, le fichier `app/Models/TODO Steph` suggère que cette partie pourrait être en cours de développement ou de refonte. Le README décrira la convention attendue.

## Contrôleurs Disponibles

Voici une liste des principaux contrôleurs de l'application et leurs rôles :

### Contrôleurs Généraux (`app/Controllers/`)
-   **`AuthentificationController.php`**:
    -   Gère le processus d'authentification des utilisateurs (affichage du formulaire de connexion, traitement de la soumission, déconnexion).
-   **`AdministrateurController.php`**:
    -   Contrôleur central pour l'espace d'administration.
    -   Affiche les menus des modules de gestion.
    -   Route les actions CRUD des modules de gestion vers les méthodes appropriées (souvent `gererTraitementModule` qui pourrait ensuite appeler des services ou DAO spécifiques).
-   **`Controller.php`** (si présent et utilisé comme base):
    -   Peut contenir des méthodes ou propriétés communes à plusieurs contrôleurs.

### Contrôleurs Publics (`app/Controllers/Public/`)
-   **`AccueilController.php`**:
    -   Gère l'affichage de la page d'accueil et d'autres pages publiques générales.

### Contrôleurs de Gestion (`app/Controllers/Gestions/`)
Ces contrôleurs sont responsables de la logique métier CRUD pour chaque entité principale de l'application. Ils sont souvent appelés par `AdministrateurController` ou directement via des routes d'API.

-   **`EnseignantsController.php`**:
    -   Gère les opérations CRUD pour les enseignants (création, lecture, mise à jour, suppression).
-   **`EtudiantsController.php`**:
    -   Gère les opérations CRUD pour les étudiants.
-   **`PersonnelAdministratifController.php`**:
    -   Gère les opérations CRUD pour le personnel administratif.

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