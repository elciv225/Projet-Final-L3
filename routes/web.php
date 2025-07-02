<?php

use App\Controllers\AuthentificationController;
use App\Controllers\Commission\DiscussionController;
use App\Controllers\Commission\HistoriqueApprobationController;
use App\Controllers\CommissionController;
use App\Controllers\MenuViews\AttributionMenuController;
use App\Controllers\MenuViews\EvaluationEtudiantController;
use App\Controllers\MenuViews\HistoriquePersonnelController;
use App\Controllers\MenuViews\ReglementInscriptionController;
use App\Controllers\MenuViews\ParametreGenerauxController;
use App\Controllers\MenuViews\PersonnelAdministratifController;
use App\Controllers\MenuViews\EnseignantController;

// Ajout du contrôleur Enseignant
use App\Controllers\MenuViews\EtudiantsController;
use App\Controllers\IndexController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Public\AuthentificationPublicController;
use App\Controllers\Public\SoumissionRapportController;

/**
 * Configuration des modules disponibles dans l'application.
 */
$configurationModules = [
    'gestion' => [
        // Le module 'utilisateurs' a été remplacé par des modules plus spécifiques
        'etudiants' => [
            'controleur' => EtudiantsController::class,
            'methodePrincipale' => 'index',
            'label' => 'Étudiants',
            'icone' => '🎓',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'inscrire' => ['methodeHttp' => 'POST', 'description' => 'Inscrire un nouvel étudiant'],
                'modifier' => ['methodeHttp' => 'POST', 'description' => 'Modifier un étudiant existant'],
                'supprimer' => ['methodeHttp' => 'POST', 'description' => 'Supprimer un ou plusieurs étudiants'],
            ]
        ],
        'enseignants' => [ // NOUVEAU: Module pour les enseignants
            'controleur' => EnseignantController::class,
            'methodePrincipale' => 'index',
            'label' => 'Enseignants',
            'icone' => '🧑‍🏫',
            'description' => 'Gestion du corps professoral',
            'traitements' => [
                'ajouter' => ['methodeHttp' => 'POST', 'description' => 'Ajouter un nouvel enseignant'],
                'modifier' => ['methodeHttp' => 'POST', 'description' => 'Modifier un enseignant'],
                'supprimer' => ['methodeHttp' => 'POST', 'description' => 'Supprimer un ou plusieurs enseignants'],
            ]
        ],
        'personnel-administratif' => [ // NOUVEAU: Module pour le personnel administratif
            'controleur' => PersonnelAdministratifController::class,
            'methodePrincipale' => 'index',
            'label' => 'Personnel Administratif',
            'icone' => '💼',
            'description' => 'Gestion du personnel administratif',
            'traitements' => [
                'ajouter' => ['methodeHttp' => 'POST', 'description' => 'Ajouter un membre du personnel'],
                'modifier' => ['methodeHttp' => 'POST', 'description' => 'Modifier un membre du personnel'],
                'supprimer' => ['methodeHttp' => 'POST', 'description' => 'Supprimer un ou plusieurs membres'],
            ]
        ],
        'parametres-generaux' => [
            'controleur' => ParametreGenerauxController::class,
            'methodePrincipale' => 'index',
            'label' => 'Paramètres Généraux',
            'icone' => '⚙️',
            'description' => 'Configuration des paramètres de l\'application',
            // ... traitements
        ],
        'historique-personnel' => [
            'controleur' => HistoriquePersonnelController::class,
            'methodePrincipale' => 'index',
            'label' => 'Historique du Personnel',
            'icone' => '📜',
            'description' => 'Consultation de l\'historique des grades et fonctions',
            // ... traitements
        ],
    ],
    'autres' => [
        'evaluation-etudiant' => [
            'controleur' => EvaluationEtudiantController::class,
            'methodePrincipale' => 'index',
            'label' => 'Évaluation Étudiants',
            'icone' => '�',
            'description' => 'Gestion des notes et évaluations',
            // ... traitements
        ],
        'attribution-menu' => [
            'controleur' => AttributionMenuController::class,
            'methodePrincipale' => 'index',
            'label' => 'Gestion des menus',
            'icone' => '☰',
            'description' => 'Attribution des accès aux menus',
            // ... traitements
        ],
        'reglement-inscription' => [
            'controleur' => ReglementInscriptionController::class,
            'methodePrincipale' => 'index',
            'label' => 'Règlement Inscription',
            'icone' => '💲',
            'description' => 'Suivi des paiements des frais d\'inscription',
            // ... traitements
        ],
    ],
    'commission' => [
        'messagerie-commission' => [
            'controleur' => DiscussionController::class,
            'methodePrincipale' => 'index',
            'label' => 'Discussion',
            'icone' => '💬',
            'description' => 'Messagerie interne de la commission',
            // ... traitements
        ],
        'historique-approbation' => [
            'controleur' => HistoriqueApprobationController::class,
            'methodePrincipale' => 'index',
            'label' => 'Historique des approbations',
            'icone' => '✅',
            'description' => 'Suivi des validations de documents',
            // ... traitements
        ],
    ]
];

if (!defined('MODULES_CONFIG')) {
    define('MODULES_CONFIG', $configurationModules);
}

/**
 * Définition des routes de l'application.
 */
$routes = [
    /* === Routes publiques === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/authentification', [AuthentificationPublicController::class, 'index']],
    ['GET', '/authentification-administration', [AuthentificationController::class, 'index']],
    ['GET', '/soumission-rapport', [SoumissionRapportController::class, 'index']],
    ['GET', '/espace-commission', [CommissionController::class, 'index']],
    ['GET', '/espace-commission/commission/discussion', [DiscussionController::class, 'index']],

    /* === Route principale de l'espace administrateur === */
    ['GET', '/index', [IndexController::class, 'index']],
];

/**
 * Génération automatique des routes pour les modules.
 */
foreach ($configurationModules as $categorie => $modulesParCategorie) {
    foreach ($modulesParCategorie as $nomModule => $configurationModule) {
        $routes[] = ['GET', "/index/$categorie/$nomModule", [IndexController::class, 'gestionMenuModules']];
    }
}

/**
 * Routes de traitement des formulaires (POST).
 */
$routes = array_merge($routes, [
    ['POST', '/authentification', [AuthentificationPublicController::class, 'authentification']],
    ['POST', '/authentification-administration', [AuthentificationController::class, 'authentification']],

    // CORRIGÉ: Routes de traitement spécifiques
    ['POST', '/traitement-etudiant', [EtudiantsController::class, 'executerAction']],
    ['POST', '/traitement-enseignant', [EnseignantController::class, 'executerAction']],
    ['POST', '/traitement-personnel-admin', [PersonnelAdministratifController::class, 'executerAction']],

    // Autres routes de traitement
    ['POST', '/charger-donnee-historique-utilisateur', [HistoriquePersonnelController::class, 'chargerPersonnelPourDonneeHistorique']],
    ['POST', '/charger-historique-personnel', [HistoriquePersonnelController::class, 'chargerDonneeHistoriquePersonnel']],
]);

return $routes;
