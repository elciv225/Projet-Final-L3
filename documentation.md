# Documentation Technique du Projet PHP MVC

Ce document détaille le fonctionnement interne, la structure, les fonctionnalités et les aspects techniques du projet de gestion basé sur un framework PHP MVC maison.

## Table des matières

1.  [Introduction et Objectif du Projet](#1-introduction-et-objectif-du-projet)
2.  [Architecture du Projet](#2-architecture-du-projet)
    *   [2.1 Architecture MVC](#21-architecture-mvc)
    *   [2.2 Structure des Dossiers](#22-structure-des-dossiers)
3.  [Fonctionnalités Détaillées](#3-fonctionnalités-détaillées)
    *   [3.1 Fonctionnalités du Framework de Base](#31-fonctionnalités-du-framework-de-base)
    *   [3.2 Fonctionnalités Spécifiques de l'Application de Gestion](#32-fonctionnalités-spécifiques-de-lapplication-de-gestion)
4.  [Analyse des Fichiers Clés](#4-analyse-des-fichiers-clés)
5.  [Configuration et Déploiement](#5-configuration-et-déploiement)
6.  [Points d'Amélioration pour la Maintenabilité](#6-points-damélioration-pour-la-maintenabilité)
7.  [Annexe: Scripts Utilitaires](#7-annexe-scripts-utilitaires)

## 1. Introduction et Objectif du Projet

Ce projet est une application web développée pour la gestion du personnel (administratif, enseignants) et des étudiants d'un établissement d'enseignement. Elle s'appuie sur un mini-framework PHP MVC (Modèle-Vue-Contrôleur) développé sur mesure. L'application offre une interface d'administration pour gérer les différentes entités et leurs interactions au sein de l'établissement.

L'objectif principal est de fournir une solution centralisée et informatisée pour :
*   Gérer les informations du personnel administratif.
*   Gérer les informations des enseignants.
*   Gérer les informations des étudiants.
*   Faciliter les opérations CRUD (Création, Lecture, Mise à jour, Suppression) sur ces entités.
*   Offrir une interface utilisateur pour l'administration de ces données.

## 2. Architecture du Projet

### 2.1 Architecture MVC

Le projet est construit autour du patron de conception **Modèle-Vue-Contrôleur (MVC)**. Cette architecture vise à séparer les responsabilités au sein de l'application :

*   **Modèle (Model)** : Représente les données et la logique métier. Il interagit avec la base de données (via les DAO - Data Access Objects) et encapsule les règles de gestion. Dans ce projet, les modèles se trouvent principalement dans `app/Models/` (bien que cela nécessite une finalisation) et la logique d'accès aux données dans `app/Dao/`.
*   **Vue (View)** : Responsable de la présentation des données à l'utilisateur. Elle reçoit les données du contrôleur et les affiche. Les vues sont des fichiers PHP situés dans `views/` et sont gérées par le composant `src/View/View.php`.
*   **Contrôleur (Controller)** : Agit comme un intermédiaire entre le Modèle et la Vue. Il reçoit les requêtes HTTP, interagit avec le Modèle pour récupérer ou modifier des données, puis sélectionne la Vue appropriée pour afficher la réponse. Les contrôleurs se trouvent dans `app/Controllers/`.

Ce découplage permet une meilleure organisation du code, facilite la maintenance et l'évolution de l'application.

### 2.2 Structure des Dossiers

La structure des dossiers du projet est organisée comme suit :

```
project/
├── app/                    # Code spécifique à l'application (logique métier)
│   ├── Controllers/        # Contrôleurs gérant les requêtes et la logique de flux
│   │   ├── Gestions/       # Contrôleurs spécifiques aux modules de gestion (étudiants, enseignants, etc.)
│   │   └── Public/         # Contrôleurs pour les pages accessibles publiquement (accueil, etc.)
│   ├── Dao/                # Data Access Objects : classes responsables de l'interaction avec la base de données
│   └── Models/             # Modèles de données (Entités/Objets métier). Conventionnellement, ce dossier contient les classes représentant les objets de domaine (ex: Etudiant, Enseignant). Actuellement, il y a un fichier TODO, indiquant un travail en cours.
├── base.sql                # Script SQL pour la création de la structure initiale de la base de données.
├── etats/                  # Contient des modèles de documents (HTML/PDF) tels que bulletins, reçus, rapports. Des fichiers TODO indiquent également un travail en cours ou une clarification nécessaire.
├── node_modules/           # Dépendances JavaScript installées via npm/yarn (pour le développement front-end).
├── public/                 # Racine web du projet, seul dossier accessible directement par les utilisateurs.
│   ├── assets/             # Fichiers statiques (CSS, JavaScript côté client, images).
│   └── index.php           # Point d'entrée unique de toutes les requêtes HTTP vers l'application.
├── routes/                 # Configuration des routes de l'application.
│   └── web.php             # Définit les correspondances entre les URLs, les méthodes HTTP et les actions des contrôleurs.
├── scripts/                # Collection de scripts utilitaires pour le développement (linting, tests, gestion d'environnement Docker).
├── src/                    # Code source du mini-framework de base.
│   ├── Database/           # Classe pour la gestion de la connexion à la base de données (PDO).
│   ├── Http/               # Composants pour la gestion des requêtes (Request) et réponses (Response) HTTP, et le Kernel qui orchestre le traitement.
│   ├── Security/           # Composants liés à la sécurité (ex: une classe Crypto pour le hachage).
│   └── View/               # Système de rendu des vues (templates PHP).
├── vendor/                 # Dépendances PHP gérées par Composer.
├── views/                  # Fichiers de templates (vues) PHP pour la présentation.
│   ├── admin/              # Vues spécifiques à l'interface d'administration.
│   ├── commission/         # Vues spécifiques à un potentiel espace "commission".
│   ├── errors/             # Pages d'erreur (ex: 404 Not Found).
│   ├── gestion/            # Vues pour les différents modules de gestion (formulaires, listes).
│   └── public/             # Vues pour les pages publiques (accueil, authentification).
├── .env                    # Fichier de configuration des variables d'environnement (non versionné, spécifique à chaque environnement).
├── .gitignore              # Spécifie les fichiers et dossiers à ignorer par Git.
├── composer.json           # Définit les dépendances PHP du projet pour Composer.
├── package.json            # Définit les dépendances JavaScript du projet pour npm/yarn.
├── Dockerfile              # Fichier principal pour construire l'image Docker de l'application.
├── Dockerfile.dev          # Dockerfile spécifique pour l'environnement de développement.
├── Dockerfile.prod         # Dockerfile spécifique pour l'environnement de production.
└── docker-compose.yml      # Définit les services, réseaux et volumes pour Docker Compose, facilitant la mise en place de l'environnement de développement.
```

## 3. Fonctionnalités Détaillées

Cette section décrit les fonctionnalités principales du projet, divisées entre celles offertes par le mini-framework de base et celles spécifiques à l'application de gestion.

### 3.1 Fonctionnalités du Framework de Base

Le mini-framework MVC maison fournit les fondations suivantes :

*   **Architecture MVC (Modèle-Vue-Contrôleur)**: Comme décrit précédemment, cette architecture sépare les préoccupations pour une meilleure organisation.
*   **Système de Routage**:
    *   Utilise la bibliothèque `nikic/fast-route` pour un routage performant et flexible.
    *   Les routes sont définies de manière centralisée dans `routes/web.php`.
    *   Le `Kernel` (`src/Http/Kernel.php`) prend en charge la mise en correspondance de l'URI de la requête avec une action de contrôleur définie.
*   **Gestion des Requêtes et Réponses HTTP**:
    *   `src/Http/Request.php`: Encapsule les données de la requête HTTP entrante (ex: `$_GET`, `$_POST`, `$_SERVER`, `$_FILES`). Fournit des méthodes pour accéder facilement à ces données.
    *   `src/Http/Response.php`: Gère la construction de la réponse HTTP. Permet de retourner des vues (HTML), des données JSON, des redirections, ou de définir des en-têtes et des codes de statut HTTP.
    *   `src/Http/Kernel.php`: Orchestre le cycle de vie d'une requête : reçoit la `Request`, utilise le routeur pour trouver le contrôleur et la méthode appropriés, exécute le contrôleur, et retourne la `Response` générée.
*   **Système de Vues/Templates**:
    *   `src/View/View.php`: Un système simple pour le rendu de fichiers de vues PHP.
    *   Permet d'injecter des données depuis le contrôleur vers la vue. Les données sont passées sous forme de tableau associatif et sont extraites en variables individuelles accessibles dans le fichier de la vue.
    *   Les vues sont stockées dans le répertoire `views/`.
*   **Connexion à la Base de Données**:
    *   `src/Database/Database.php`: Fournit une connexion à la base de données via PDO.
    *   Utilise un design pattern Singleton pour s'assurer qu'une seule instance de la connexion PDO est créée et partagée à travers l'application.
    *   Les informations de connexion (hôte, nom de la base, utilisateur, mot de passe) sont chargées à partir des variables d'environnement définies dans le fichier `.env`.
*   **Gestion des Erreurs de Base**:
    *   Le framework dispose de mécanismes rudimentaires pour la gestion des erreurs, par exemple, l'affichage d'une page 404 (voir `views/errors/404.php`) lorsque aucune route ne correspond à l'URI demandée.
    *   Le `Controller.php` de base (`app/Controllers/Controller.php`) fournit des méthodes `succes()`, `error()`, `info()` pour retourner des réponses JSON standardisées, utiles notamment pour les requêtes AJAX.
*   **Autoloader PSR-4**:
    *   Grâce à Composer, le projet utilise l'autoloader PSR-4. Cela signifie que les classes sont chargées automatiquement en fonction de leur namespace et de leur emplacement dans l'arborescence des fichiers, sans nécessiter d'instructions `require` manuelles.

### 3.2 Fonctionnalités Spécifiques de l'Application de Gestion

L'application de gestion construite sur ce framework offre les fonctionnalités suivantes :

*   **Authentification des Utilisateurs**:
    *   Gérée par `app/Controllers/AuthentificationPublicController.php`.
    *   Permet aux utilisateurs de se connecter et se déconnecter.
    *   Utilise les sessions PHP (`session_start()` dans `public/index.php`) pour maintenir l'état de connexion.
    *   Les vues associées sont `views/public/authentification.php`.
*   **Espace Administrateur**:
    *   Accessible via l'URL `/espace-administrateur`.
    *   Contrôlé principalement par `app/Controllers/AdministrateurController.php`.
    *   Fournit une interface centralisée pour accéder aux différents modules de gestion.
    *   La vue principale est `views/espace-administrateur.php`, qui intègre un menu et une zone de contenu dynamique.
*   **Gestion Modulaire (Personnel, Enseignants, Étudiants)**:
    *   Le système est conçu pour gérer différentes entités (personnel administratif, enseignants, étudiants) de manière modulaire.
    *   **Configuration des modules**: La configuration de ces modules (leur contrôleur, méthode principale, traitements CRUD, labels, icônes) est définie dans le tableau `$configurationModules` au sein de `routes/web.php`. Cette configuration est ensuite stockée dans une constante `MODULES_CONFIG`.
    *   **Affichage des modules**: `AdministrateurController::gestionMenuModules()` (héritée de `Controller::gestionMenuModules`) utilise cette configuration pour dynamiquement charger le contenu du module sélectionné dans l'interface d'administration. Il appelle la `methodePrincipale` du contrôleur du module concerné.
    *   **Traitement des actions CRUD**: `AdministrateurController::gererTraitementModule()` (héritée de `Controller::gererTraitementModule`) gère les soumissions de formulaires (ajouter, modifier, supprimer) pour les modules. Il identifie le module, le type de traitement, et appelle la méthode correspondante dans le contrôleur de gestion spécifique.
*   **Contrôleurs de Gestion Spécifiques (`app/Controllers/Gestions/`)**:
    *   `EnseignantsController.php`, `EtudiantsController.php`, `PersonnelAdministratifController.php`.
    *   Chaque contrôleur est responsable de la logique métier CRUD pour son entité respective.
    *   Ils contiennent des méthodes comme `index()` (pour afficher la liste/interface de gestion), `ajouter()`, `modifier()`, `supprimer()`.
    *   Ces méthodes interagissent typiquement avec les DAO pour manipuler les données et retournent des réponses (souvent JSON pour AJAX ou des redirections).
*   **Data Access Objects (DAO)**:
    *   `app/Dao/DAO.php`: Une classe abstraite fournissant des méthodes CRUD génériques (`recupererParId`, `recupererTous`, `creer`, `mettreAJour`, `supprimer`, `rechercher`, `compter`, `executerRequete`).
    *   **Convention pour les DAO Concrets**: Il est attendu que des classes DAO spécifiques héritent de `DAO.php` pour chaque entité (par exemple, `EtudiantDAO.php`). Ces classes concrètes spécifieraient le nom de la table, le nom de la classe du modèle associé, et la clé primaire, puis pourraient surcharger ou ajouter des méthodes si des requêtes plus spécifiques sont nécessaires.
*   **Modèles de Données (`app/Models/`)**:
    *   Ce répertoire est destiné à contenir les classes PHP représentant les entités de l'application (ex: `Etudiant`, `Enseignant`). Ces classes agiraient comme des objets de transfert de données (DTOs) ou des objets métier.
    *   Le fichier `app/Models/TODO Steph` indique que cette partie est à développer ou à finaliser. Idéalement, les méthodes des DAO (comme `recupererParId` ou `recupererTous`) hydrateraient des instances de ces modèles.
*   **Génération Dynamique des Routes de Gestion**:
    *   Comme mentionné dans la section Routage du README et visible dans `routes/web.php`, les routes pour les modules de gestion (affichage et traitements CRUD) sont générées automatiquement à partir de la configuration `$configurationModules`. Cela réduit la redondance et facilite l'ajout de nouveaux modules.
    *   Des routes pour l'affichage dans l'espace administrateur (ex: `/espace-administrateur/gestion/etudiants`) et des routes d'API plus directes (ex: `/gestion/etudiants/ajouter`) sont créées.
*   **Gestion des Sessions et Messages Flash**:
    *   L'utilisation de `session_start()` est présente. Bien qu'un système de messages flash explicite ne soit pas détaillé, il est courant dans de telles applications de stocker des messages de succès ou d'erreur en session pour les afficher après une redirection.
*   **Scripts Utilitaires (`scripts/`)**:
    *   Le projet fournit divers scripts shell pour aider au développement : linting de code (PHP, CSS, JS), exécution de tests (présomptif), et gestion des configurations d'environnement Docker.

## 4. Analyse des Fichiers Clés

Cette section met en lumière les fichiers les plus importants du projet et leur rôle.

*   **`public/index.php`**:
    *   **Rôle**: Point d'entrée unique de toutes les requêtes HTTP vers l'application.
    *   **Fonctionnement**:
        1.  Démarre la session PHP (`session_start()`).
        2.  Définit des en-têtes de contrôle du cache.
        3.  Définit la constante `BASE_PATH` (chemin racine du projet).
        4.  Charge l'autoloader de Composer (`vendor/autoload.php`).
        5.  Charge les variables d'environnement depuis le fichier `.env` en utilisant `Dotenv\Dotenv`.
        6.  Crée une instance de `System\Http\Request` pour encapsuler la requête actuelle.
        7.  Instancie le `System\Http\Kernel`.
        8.  Appelle la méthode `handle()` du Kernel, qui traite la requête et retourne un objet `System\Http\Response`.
        9.  Envoie la réponse au client via la méthode `send()` de l'objet `Response`.

*   **`routes/web.php`**:
    *   **Rôle**: Définit toutes les routes de l'application et configure les modules de gestion.
    *   **Fonctionnement**:
        1.  Définit un tableau `$configurationModules` qui structure les modules de gestion (personnel, enseignants, étudiants). Chaque module a un contrôleur, une méthode principale pour l'affichage, un label, une icône, une description et une liste de `traitements` (actions CRUD) avec leur méthode HTTP.
        2.  Définit une constante `MODULES_CONFIG` avec cette configuration pour un accès global.
        3.  Initialise un tableau `$routes` avec les routes statiques (accueil, authentification, espace administrateur).
        4.  Itère sur `$configurationModules` pour générer dynamiquement les routes pour chaque module et ses traitements :
            *   Routes `GET` pour afficher le module dans l'espace administrateur (ex: `/espace-administrateur/gestion/etudiants`), gérées par `AdministrateurController::gestionMenuModules`.
            *   Routes (généralement `POST`) pour les actions CRUD au sein de l'espace administrateur (ex: `/espace-administrateur/gestion/etudiants/ajouter`), gérées par `AdministrateurController::gererTraitementModule`.
            *   Routes d'API directes pour les traitements CRUD (ex: `/gestion/etudiants/ajouter`), gérées par le contrôleur spécifique du module (ex: `EtudiantsController::ajouter`).
        5.  Fusionne les routes de traitement de formulaires (comme la soumission du formulaire d'authentification).
        6.  Retourne le tableau complet des routes, qui sera utilisé par le `Kernel` et `nikic/fast-route`.

*   **`app/Controllers/Controller.php`**:
    *   **Rôle**: Sert de contrôleur de base pour les autres contrôleurs de l'application.
    *   **Fonctionnalités héritables**:
        *   Initialise un objet `Request` dans son constructeur.
        *   Fournit des méthodes `succes()`, `error()`, `info()` pour standardiser les réponses JSON (utiles pour AJAX).
        *   `getAvailableModules()`: Récupère la configuration des modules (depuis `MODULES_CONFIG`) pour les menus.
        *   `gestionMenuModules()`: Logique générique pour afficher un module configuré. Il identifie le module via l'URI, charge sa configuration, instancie son contrôleur, appelle sa méthode principale, et rend la vue appropriée (soit JSON pour AJAX, soit une vue complète).
        *   `getModuleConfig()`: Méthode privée pour récupérer la configuration d'un module spécifique.
        *   `gererTraitementModule()`: Logique générique pour gérer une action CRUD d'un module. Il identifie le module et le traitement, instancie le contrôleur du module, et appelle la méthode de traitement correspondante. Redirige ou retourne JSON selon le type de requête.

*   **`app/Controllers/AdministrateurController.php`**:
    *   **Rôle**: Gère l'espace d'administration principal et délègue la gestion des modules.
    *   **Fonctionnement**:
        *   Hérite de `App\Controllers\Controller`.
        *   `index()`: Affiche la page d'accueil de l'espace administrateur.
        *   `gestionMenuModules()`: Appelle la méthode parente `gestionMenuModules()` pour afficher le contenu d'un module spécifique au sein de la vue `espace-administrateur`.
        *   `gererTraitementModule()`: Appelle la méthode parente `gererTraitementModule()` pour exécuter une action CRUD et gérer la redirection ou la réponse JSON.

*   **`src/Http/Kernel.php`**:
    *   **Rôle**: Cœur du framework pour le traitement des requêtes HTTP.
    *   **Fonctionnement**:
        1.  La méthode `handle(Request $request)` est le point central.
        2.  Utilise `nikic/fast-route` pour dispatcher la requête. Les routes sont chargées depuis `routes/web.php`.
        3.  Analyse le résultat du dispatching :
            *   `Dispatcher::NOT_FOUND`: Retourne une réponse 404 (vue `errors/404.php`).
            *   `Dispatcher::METHOD_NOT_ALLOWED`: Retourne une réponse 405.
            *   `Dispatcher::FOUND`: Récupère le contrôleur, la méthode et les variables d'URL. Instancie le contrôleur et appelle la méthode avec les variables. Retourne la `Response` produite par le contrôleur.
            *   Par défaut (cas d'erreur imprévu) : Retourne une réponse 500.

*   **`.env` (Exemple de contenu et utilisation)**:
    *   **Rôle**: Stocker les configurations spécifiques à l'environnement (base de données, clés API, modes de débogage, etc.) de manière sécurisée (non versionné).
    *   **Utilisation**: Chargé par `Dotenv\Dotenv` dans `public/index.php`. Les variables sont accessibles via `$_ENV` ou `getenv()`.
    *   **Exemples de variables**: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `APP_ENV`, `APP_DEBUG`, `APP_URL`.

*   **`composer.json`**:
    *   **Rôle**: Fichier de configuration pour Composer, le gestionnaire de dépendances PHP.
    *   **Contenu**:
        *   `require`: Liste les dépendances du projet (ex: `vlucas/phpdotenv`, `nikic/fast-route`, `symfony/var-dumper`, `phpmailer/phpmailer`).
        *   `autoload`: Configure l'autoloader PSR-4, mappant les namespaces aux répertoires (`System\` vers `src/`, `App\` vers `app/`, etc.).
        *   `authors`: Informations sur les auteurs du projet.

*   **`package.json`**:
    *   **Rôle**: Fichier de configuration pour npm/yarn, le gestionnaire de paquets JavaScript.
    *   **Contenu**:
        *   `devDependencies`: Dépendances utilisées pour le développement (ex: `stylelint` pour le linting CSS).
        *   `dependencies`: Dépendances JavaScript utilisées par l'application côté client (ex: `gsap` pour les animations).

*   **`Dockerfile` (et `Dockerfile.dev`, `Dockerfile.prod`)**:
    *   **Rôle**: Décrivent comment construire les images Docker pour l'application.
    *   **`Dockerfile` (principal, semble être celui de développement)**:
        *   Basé sur `php:8.3-apache`.
        *   Installe des dépendances système (`libpng-dev`, `git`, etc.).
        *   Installe des extensions PHP (`gd`, `pdo_mysql`, `intl`, `xdebug`, `redis`).
        *   Active des modules Apache (`rewrite`).
        *   Configure PHP (`memory_limit`, `upload_max_filesize`, Xdebug, OPcache).
        *   Installe Composer.
        *   Configure le `DocumentRoot` d'Apache sur `/var/www/html/public`.
        *   Gère les permissions.
    *   `Dockerfile.dev` et `Dockerfile.prod` (non lus, mais leur nom suggère des configurations spécifiques pour le développement et la production). Les scripts `scripts/switch-dockerfile.sh` permettent de basculer entre ces configurations.

*   **`docker-compose.yml`**:
    *   **Rôle**: Définit et configure les services multi-conteneurs pour l'environnement de développement.
    *   **Services définis**:
        *   `app`: L'application PHP, construite à partir du `Dockerfile`. Monte le code source local, expose un port, configure des variables d'environnement (dont celles pour Xdebug et MailHog), et dépend de `db` et `mailhog`.
        *   `db`: Service MySQL, utilise l'image `mysql:8.0`. Expose le port MySQL, configure les identifiants de la base, et utilise un volume pour la persistance des données.
        *   `phpmyadmin`: Service PhpMyAdmin pour gérer la base de données.
        *   `redis`: Service Redis (optionnel).
        *   `mailhog`: Service MailHog pour intercepter les emails en développement.
    *   Définit des volumes (`db_data_local`, `redis_data_local`) et un réseau (`app_network`).

*   **`base.sql`**:
    *   **Rôle**: Contient les instructions SQL `CREATE TABLE` pour initialiser la structure de la base de données du projet.
    *   **Détails**: Définit de nombreuses tables (ex: `rapport_etudiant`, `utilisateur`, `enseignant`, `etudiant`, `ue`, `evaluation`, etc.) avec leurs colonnes, types de données, clés primaires et clés étrangères. Ce fichier est crucial pour comprendre le schéma de la base de données.

## 5. Configuration et Déploiement

Cette section décrit comment configurer l'application pour différents environnements et donne un aperçu du processus de déploiement.

### 5.1 Configuration via `.env`

La configuration principale de l'application est gérée par des variables d'environnement. Celles-ci sont définies dans un fichier `.env` à la racine du projet. Ce fichier n'est pas versionné (il est listé dans `.gitignore`) car il contient des informations sensibles et spécifiques à chaque environnement.

**Variables d'environnement typiques :**

*   **Base de données**:
    *   `DB_HOST`: Hôte de la base de données (ex: `mysql_db` pour le service Docker, `localhost` pour une base locale).
    *   `DB_PORT`: Port de la base de données (ex: `3306`).
    *   `DB_NAME`: Nom de la base de données.
    *   `DB_USER`: Utilisateur de la base de données.
    *   `DB_PASSWORD`: Mot de passe de l'utilisateur.
*   **Application**:
    *   `APP_ENV`: Environnement de l'application (ex: `dev`, `prod`, `local`).
    *   `APP_DEBUG`: Activer (`true`) ou désactiver (`false`) le mode débogage.
    *   `APP_URL`: URL de base de l'application (ex: `http://localhost:8081`).
    *   `APP_KEY`: Clé de chiffrement pour l'application (utilisée par `src/Security/Crypto.php`). **Elle doit commencer par `base64:` et la partie après le préfixe doit être une chaîne de 32 octets encodée en Base64.**
*   **Email (pour MailHog en développement)**:
    *   `MAIL_MAILER`: `smtp`
    *   `MAIL_HOST`: `mailhog` (nom du service Docker)
    *   `MAIL_PORT`: `1025`
    *   `MAIL_USERNAME`: `null`
    *   `MAIL_PASSWORD`: `null`
    *   `MAIL_ENCRYPTION`: `null`

**Gestion des fichiers `.env` pour différents environnements :**

Le projet fournit des scripts pour faciliter le changement de configuration :

*   **`scripts/switch-env.sh <fichier_env_source>`**: Ce script copie le contenu de `<fichier_env_source>` (par exemple, `.env.dev` ou `.env.prod`) vers le fichier `.env` actif.
    *   **Exemple pour passer en production (après avoir créé un `.env.prod`)**:
        ```bash
        ./scripts/switch-env.sh .env.prod
        ```
    *   Il est recommandé de créer des fichiers modèles comme `.env.example`, `.env.dev.example`, `.env.prod.example` à versionner, que les développeurs copieront et adapteront.

### 5.2 Déploiement avec Docker

Le projet est configuré pour être déployé à l'aide de Docker et Docker Compose, ce qui simplifie grandement la mise en place de l'environnement d'exécution.

**Fichiers Docker clés :**

*   **`Dockerfile`**: Utilisé par défaut par `docker-compose.yml`. Il est configuré pour un environnement de développement (avec Xdebug, OPcache en mode validation fréquente, etc.).
*   **`Dockerfile.dev`**: Probablement identique ou très similaire au `Dockerfile` principal, destiné explicitement au développement.
*   **`Dockerfile.prod`**: Destiné à l'environnement de production. Il devrait être optimisé pour la performance et la sécurité (ex: Xdebug désactivé, OPcache agressivement configuré, moins de logs).
*   **`docker-compose.yml`**: Définit les services pour l'environnement de développement local (application, base de données, PhpMyAdmin, Redis, MailHog).
*   **`docker-compose.prod.yml` (hypothétique)**: Un fichier similaire pourrait exister ou être créé pour la production, définissant uniquement les services nécessaires (ex: application, base de données, Redis) avec des configurations optimisées pour la production.

**Processus de déploiement (exemple général) :**

1.  **Préparation de l'environnement de production**:
    *   S'assurer que Docker et Docker Compose sont installés sur le serveur de production.
    *   Créer les fichiers de configuration spécifiques à la production si ce n'est pas déjà fait :
        *   `.env.prod` (contenant les identifiants de base de données de production, `APP_ENV=prod`, `APP_DEBUG=false`, une `APP_KEY` de production sécurisée).
        *   `Dockerfile.prod` (optimisé pour la production).
        *   Optionnellement, un `docker-compose.prod.yml`.

2.  **Basculement des configurations (si nécessaire, via les scripts)**:
    *   Sur le serveur de production ou dans le pipeline CI/CD :
        ```bash
        ./scripts/switch-env.sh .env.prod
        ./scripts/switch-dockerfile.sh Dockerfile.prod
        # Si un docker-compose spécifique à la prod est utilisé :
        # ./scripts/switch-docker-compose.sh docker-compose.prod.yml
        ```
    *   **Alternative**: Le `docker-compose.yml` peut être conçu pour utiliser des variables d'environnement pour choisir le `Dockerfile` ou charger des configurations spécifiques, évitant le besoin de switcher les fichiers manuellement lors du build.

3.  **Construction et lancement des conteneurs Docker**:
    *   Si un `docker-compose.prod.yml` est utilisé :
        ```bash
        docker-compose -f docker-compose.prod.yml up -d --build
        ```
    *   Si le `docker-compose.yml` principal est utilisé (après avoir switché les Dockerfile/.env) :
        ```bash
        docker-compose up -d --build
        ```
    *   L'option `--build` force la reconstruction des images. L'option `-d` lance les conteneurs en arrière-plan.

4.  **Configuration post-déploiement**:
    *   Exécuter les migrations de base de données (si un système de migration est en place, sinon importer `base.sql` lors de la première initialisation de la base de données de production).
    *   Vider les caches de l'application si nécessaire.
    *   Configurer les tâches cron si l'application en a.

**Gestion des mises à jour**:

*   Récupérer la dernière version du code.
*   Reconstruire les images Docker si le `Dockerfile` ou le code source a changé : `docker-compose pull && docker-compose up -d --build` (ou la version avec `-f docker-compose.prod.yml`).
*   Appliquer les migrations de base de données.

### 5.3 Scripts Utilitaires pour la Configuration

Comme détaillé dans l'[Annexe: Scripts Utilitaires](#7-annexe-scripts-utilitaires), les scripts suivants sont particulièrement importants pour la gestion de la configuration entre les environnements :

*   `./scripts/switch-dockerfile.sh <nouveau_dockerfile>`
*   `./scripts/switch-docker-compose.sh <nouveau_compose_file>`
*   `./scripts/switch-env.sh <nouveau_env_file>`
*   `./scripts/switch-ignore.sh <nouveau_ignore_file>` (moins courant pour le déploiement direct, mais peut affecter ce qui est inclus dans le contexte de build Docker).

Il est crucial de bien comprendre le rôle de ces scripts et de les utiliser de manière appropriée lors du passage d'un environnement de développement à un environnement de test ou de production.

## 6. Points d'Amélioration pour la Maintenabilité

Plusieurs aspects du projet peuvent être améliorés pour accroître sa maintenabilité, sa robustesse et sa facilité d'évolution.

*   **Finalisation des Modèles de Données (`app/Models/`)**:
    *   **Constat**: Le répertoire `app/Models/` contient un fichier `TODO Steph`, indiquant que la création des classes de modèles (entités) est inachevée. Actuellement, les DAO semblent retourner des objets `stdClass` ou des tableaux hydratés directement par PDO, plutôt que des instances de modèles métier fortement typés.
    *   **Suggestion**: Définir des classes PHP pour chaque entité de la base de données (ex: `Utilisateur`, `Etudiant`, `Enseignant`, `RapportEtudiant`, etc.). Ces classes devraient avoir des propriétés correspondant aux colonnes des tables et potentiellement des méthodes pour la logique métier spécifique à l'entité. Les méthodes des DAO (comme `recupererParId`, `recupererTous`) devraient être mises à jour pour retourner des instances de ces modèles (en utilisant `PDO::FETCH_CLASS` avec le nom de la classe modèle appropriée).
    *   **Avantages**: Améliore la lisibilité, la robustesse (typage), et facilite le développement (autocomplétion, refactoring).

*   **Implémentation des DAO Concrets**:
    *   **Constat**: Bien qu'il existe une classe abstraite `app/Dao/DAO.php` très complète, il n'est pas explicitement indiqué si des DAO concrets (ex: `EtudiantDAO.php`, `EnseignantDAO.php`) sont pleinement implémentés pour chaque entité.
    *   **Suggestion**: S'assurer que pour chaque entité gérée, une classe DAO spécifique existe, héritant de `App\Dao\DAO`. Ces classes concrètes passeraient le nom de la table, le nom de la classe modèle, et la clé primaire au constructeur parent. Elles pourraient également contenir des méthodes spécifiques si des requêtes plus complexes que les opérations CRUD de base sont nécessaires pour une entité particulière.
    *   **Avantages**: Meilleure organisation de la couche d'accès aux données, séparation des préoccupations.

*   **Mise en Place et Développement de Tests Automatisés**:
    *   **Constat**: Un script `scripts/run-tests.sh` existe, qui cherche des fichiers PHP dans un dossier `./tests` et les exécute. Cependant, le contenu et la couverture actuels des tests ne sont pas connus.
    *   **Suggestion**:
        *   Adopter un framework de test PHP standard comme PHPUnit.
        *   Écrire des tests unitaires pour les composants critiques (classes du framework `src/`, méthodes des contrôleurs, logique métier dans les modèles, méthodes des DAO).
        *   Écrire des tests d'intégration pour vérifier l'interaction entre les différents composants (ex: une requête HTTP complète, de la route au contrôleur, puis au DAO et à la base de données).
        *   Intégrer les tests dans un pipeline CI/CD si possible.
    *   **Avantages**: Garantit la non-régression lors des modifications, améliore la confiance dans le code, sert de documentation vivante.

*   **Amélioration de la Documentation du Code (PHPDoc)**:
    *   **Constat**: Certains fichiers ont des PHPDoc, mais une révision globale pourrait être bénéfique.
    *   **Suggestion**: Compléter et standardiser les blocs PHPDoc pour toutes les classes, méthodes et propriétés. Décrire clairement les paramètres, les valeurs de retour, les exceptions possibles et le rôle de chaque élément.
    *   **Avantages**: Facilite la compréhension du code par les développeurs (actuels et futurs) et permet la génération automatique de documentation.

*   **Gestion des Erreurs et Journalisation (Logging)**:
    *   **Constat**: La gestion des erreurs est basique (pages 404/500, réponses JSON via `Controller::error()`). La journalisation semble se limiter à `error_log()` dans certains cas.
    *   **Suggestion**:
        *   Mettre en place un système de gestion des exceptions plus robuste à l'échelle de l'application.
        *   Utiliser une bibliothèque de journalisation dédiée (ex: Monolog) pour enregistrer les erreurs et les événements importants de manière structurée, avec différents niveaux de criticité (DEBUG, INFO, WARNING, ERROR).
        *   Configurer des gestionnaires de logs pour écrire dans des fichiers, des services externes, etc., selon l'environnement.
    *   **Avantages**: Facilite le débogage, la surveillance de l'application en production et l'analyse des problèmes.

*   **Révision de la Sécurité**:
    *   **Validation des Entrées**: S'assurer que toutes les données provenant des utilisateurs (formulaires, paramètres d'URL) sont systématiquement validées et nettoyées (sanitized) avant d'être utilisées, pour prévenir les injections (SQL, XSS, etc.).
    *   **Protection contre les Injections SQL**: L'utilisation de requêtes préparées avec PDO dans `app/Dao/DAO.php` est un bon point de départ. Maintenir cette pratique.
    *   **Protection XSS**: S'assurer que toutes les données affichées dans les vues sont correctement échappées (ex: avec `htmlspecialchars`).
    *   **Protection CSRF**: Implémenter des tokens CSRF pour tous les formulaires qui modifient l'état de l'application (POST, PUT, DELETE).
    *   **Gestion des Mots de Passe**: Le fichier `base.sql` indique `mot_de_passe VARCHAR(255) -- Should store hashed passwords`. S'assurer que les mots de passe sont bien hachés avec des algorithmes modernes et robustes (ex: `password_hash()` et `password_verify()` de PHP).
    *   **`src/Security/Crypto.php`**: Cette classe utilise `openssl_encrypt` et `openssl_decrypt` avec AES-256-GCM, ce qui est une bonne pratique pour le chiffrement symétrique. La clé est chargée depuis `.env`. S'assurer que la `APP_KEY` est générée de manière sécurisée et unique pour chaque environnement.
    *   **Contrôle d'accès**: Vérifier la robustesse des mécanismes de contrôle d'accès pour les différentes sections et fonctionnalités, notamment l'espace administrateur et les actions CRUD.
    *   **Avantages**: Renforce la sécurité globale de l'application.

*   **Clarification de la Gestion des Environnements**:
    *   **Constat**: Des scripts (`scripts/switch-dockerfile.sh`, etc.) existent pour changer d'environnement, mais leur utilisation et la gestion des fichiers `.env` correspondants pourraient être mieux documentées.
    *   **Suggestion**: Documenter clairement la procédure pour passer d'un environnement à un autre (développement, production, test), y compris la gestion des `Dockerfile` et des fichiers `.env` associés (ex: `.env.dev`, `.env.prod`).
    *   **Avantages**: Simplifie la configuration pour les développeurs et pour le déploiement.

*   **Conventions de Nommage et de Codage**:
    *   **Constat**: Le code semble globalement cohérent, mais une vérification formelle des conventions (PSR-12 par exemple) serait utile. Le nommage est en français, ce qui est un choix, mais il faut s'assurer de sa cohérence.
    *   **Suggestion**: Adopter et appliquer un standard de codage (ex: PSR-12). Utiliser des outils comme PHP CS Fixer ou PHP_CodeSniffer pour automatiser la vérification et la correction.
    *   **Avantages**: Améliore la lisibilité et la cohérence du code.

*   **Mise à Jour des Dépendances**:
    *   **Constat**: Les versions des dépendances dans `composer.json` et `package.json` pourraient nécessiter une vérification.
    *   **Suggestion**: Vérifier régulièrement les versions des dépendances et les mettre à jour si cela est pertinent et sécurisé, pour bénéficier des dernières fonctionnalités et correctifs de sécurité.
    *   **Avantages**: Maintient la sécurité et la modernité du projet.

*   **Fichiers `etats/` et TODOs Associés**:
    *   **Constat**: Le dossier `etats/` contient des modèles HTML pour divers documents (bulletins, reçus) et des fichiers `TODO` (`etats/TODO`, `etats/TODO Katie`) indiquant un travail inachevé ou nécessitant clarification.
    *   **Suggestion**:
        *   Documenter clairement le rôle et l'utilisation de chaque fichier modèle dans `etats/`.
        *   Préciser comment ces modèles sont utilisés pour générer les documents finaux (ex: conversion HTML vers PDF, outils utilisés).
        *   Résoudre les `TODO` en complétant les états ou en clarifiant les besoins.
    *   **Avantages**: Clarifie une fonctionnalité importante de l'application et assure sa complétude.

*   **Optimisation des Requêtes et de la Base de Données**:
    *   **Constat**: Le `base.sql` montre un schéma de base de données complexe. `DAO.php` fournit des méthodes de base.
    *   **Suggestion**: Analyser les requêtes SQL générées, en particulier pour les listages et recherches complexes. S'assurer que des index appropriés sont présents sur les colonnes fréquemment utilisées dans les clauses `WHERE`, `JOIN` et `ORDER BY` pour optimiser les performances.
    *   **Avantages**: Améliore la performance de l'application, surtout avec un volume de données croissant.

*   **Refactoring Potentiel du `Controller.php`**:
    *   **Constat**: Le `Controller.php` de base contient une logique significative pour `gestionMenuModules` et `gererTraitementModule`.
    *   **Suggestion**: Évaluer si certaines parties de cette logique pourraient être extraites dans des classes de service dédiées ou des traits pour alléger le contrôleur de base et améliorer la séparation des responsabilités, surtout si le nombre de types de modules ou de traitements augmente.
    *   **Avantages**: Améliore la testabilité et la flexibilité du système de gestion des modules.

## 7. Annexe: Scripts Utilitaires

Le dossier `scripts/` contient plusieurs scripts shell pour faciliter les tâches de développement et de maintenance.

*   **`lint-css.sh`**:
    *   **Rôle**: Exécute Stylelint pour vérifier la syntaxe et les conventions de style des fichiers CSS du projet.
    *   **Utilisation typique**: `./scripts/lint-css.sh`

*   **`lint-js.sh`**:
    *   **Rôle**: Probablement destiné à exécuter un linter JavaScript (comme ESLint), bien que la configuration ESLint (`eslint.config.js`) soit présente à la racine, le script lui-même pourrait nécessiter une vérification pour confirmer son contenu exact.
    *   **Utilisation typique**: `./scripts/lint-js.sh`

*   **`lint-php.sh`**:
    *   **Rôle**: Exécute un linter PHP (probablement PHP_CodeSniffer ou PHP CS Fixer, bien que non explicitement spécifié dans le script de base, mais c'est une pratique courante) pour vérifier la syntaxe et les conventions de codage PHP.
    *   **Utilisation typique**: `./scripts/lint-php.sh`

*   **`run-tests.sh`**:
    *   **Rôle**: Exécute les tests PHP présents dans le dossier `./tests`. Il parcourt les fichiers PHP de ce dossier et les exécute avec l'interpréteur PHP.
    *   **Utilisation typique**: `./scripts/run-tests.sh`
    *   **Note**: Ce script est basique. Pour une suite de tests plus robuste, l'intégration d'un framework comme PHPUnit est recommandée (voir section Points d'Amélioration).

*   **`set-remote.sh`**:
    *   **Rôle**: Semble être un script pour configurer l'URL du dépôt distant Git (`origin`). Utile pour la configuration initiale du projet ou pour changer de dépôt distant.
    *   **Utilisation typique**: `./scripts/set-remote.sh <URL_DU_DEPOT_GIT>`

*   **`switch-docker-compose.sh`**:
    *   **Rôle**: Permet de remplacer le fichier `docker-compose.yml` actif par un autre fichier de configuration Docker Compose (ex: `docker-compose.prod.yml`).
    *   **Utilisation typique**: `./scripts/switch-docker-compose.sh docker-compose.prod.yml`
    *   **Note**: Utile pour basculer entre différentes configurations de services Docker (développement, production, etc.).

*   **`switch-dockerfile.sh`**:
    *   **Rôle**: Permet de remplacer le fichier `Dockerfile` actif par un autre Dockerfile (ex: `Dockerfile.prod`).
    *   **Utilisation typique**: `./scripts/switch-dockerfile.sh Dockerfile.prod`
    *   **Note**: Utile pour changer la configuration de construction de l'image Docker principale de l'application.

*   **`switch-env.sh`**:
    *   **Rôle**: Permet de remplacer le fichier `.env` actif par un autre fichier d'environnement (ex: `.env.prod`).
    *   **Utilisation typique**: `./scripts/switch-env.sh .env.prod`
    *   **Note**: Crucial pour gérer les configurations spécifiques à chaque environnement (base de données, clés API, etc.).

*   **`switch-ignore.sh`**:
    *   **Rôle**: Permet de remplacer le fichier `.gitignore` actif par un autre fichier d'ignorance Git (ex: `.gitignore.prod`).
    *   **Utilisation typique**: `./scripts/switch-ignore.sh .gitignore.prod`
    *   **Note**: Peut être utile si les fichiers à ignorer diffèrent significativement entre les environnements de développement et de production.

*(La section 5. Configuration et Déploiement sera complétée pour intégrer l'usage de ces scripts dans les workflows de configuration et de passage d'un environnement à un autre.)*
