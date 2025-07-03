<?php

use App\Controllers\AuthentificationController;
use App\Controllers\Commission\DiscussionController;
use App\Controllers\Commission\HistoriqueApprobationController;
use App\Controllers\MenuViews\AttributionMenuController;
use App\Controllers\MenuViews\EvaluationEtudiantController;
use App\Controllers\MenuViews\HistoriquePersonnelController;
use App\Controllers\MenuViews\ReglementInscriptionController;
use App\Controllers\MenuViews\ParametreGenerauxController;
use App\Controllers\MenuViews\PersonnelAdministratifController;
use App\Controllers\MenuViews\EnseignantController;

// Ajout du contrôleur Enseignant
use App\Controllers\MenuViews\EtudiantsController;
use App\Controllers\MenuViews\AuditsController;
use App\Controllers\IndexController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Public\EspaceUtilisateurController;
use App\Controllers\Public\InscriptionController;
use App\Controllers\Public\SoumissionRapportController;

// Routes pour l'authentification publique
// Note: Les routes d'authentification publique sont définies dans la section "Routes publiques"

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
            'icone' => '📝',
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
        'audits' => [
            'controleur' => AuditsController::class,
            'methodePrincipale' => 'index',
            'label' => 'Audits du Système',
            'icone' => '🔍',
            'description' => 'Journal des activités du système',
            'traitements' => [
                'get-audits' => ['methodeHttp' => 'POST', 'description' => 'Récupérer les données d\'audit'],
                'get-utilisateurs' => ['methodeHttp' => 'POST', 'description' => 'Récupérer la liste des utilisateurs'],
                'export-pdf' => ['methodeHttp' => 'POST', 'description' => 'Exporter les données au format PDF'],
                'export-excel' => ['methodeHttp' => 'POST', 'description' => 'Exporter les données au format Excel'],
            ]
        ],
        'confirmation-rapports' => [
            'controleur' => \App\Controllers\MenuViews\ConfirmationRapportsController::class,
            'methodePrincipale' => 'index',
            'label' => 'Confirmation des Rapports',
            'icone' => '✓',
            'description' => 'Validation et approbation des rapports de stage',
            'traitements' => [
                'get-rapports' => ['methodeHttp' => 'POST', 'description' => 'Récupérer les rapports avec pagination'],
                'get-etudiants' => ['methodeHttp' => 'POST', 'description' => 'Récupérer la liste des étudiants'],
                'executer-action' => ['methodeHttp' => 'POST', 'description' => 'Exécuter une action sur les rapports'],
            ]
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
    /* === Routes publiques (GET) === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/espace-utilisateur', [EspaceUtilisateurController::class, 'index']],
    ['GET', '/inscription', [InscriptionController::class, 'index']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],
    ['GET', '/soumission-rapport', [SoumissionRapportController::class, 'index']],
    ['GET', '/commission/discussions', [DiscussionController::class, 'index']],
    ['GET', '/commission/discussion', [DiscussionController::class, 'afficherDiscussion']],
    ['POST', '/commission/ajouter-message', [DiscussionController::class, 'ajouterMessage']],
    ['POST', '/commission/voter', [DiscussionController::class, 'voter']],

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
    /* === Routes d'authentification (POST) === */
    // Authentification par étapes (nouvelle méthode)
    ['POST', '/inscription', [InscriptionController::class, 'authentification']],

    // Routes pour l'authentification traditionnelle (formulaires standards)

    // Authentification administration
    ['POST', '/authentification', [AuthentificationController::class, 'authentification']],
    ['POST', '/mot-de-passe-oublie', [AuthentificationController::class, 'motDePasseOublie']],
    ['POST', '/reinitialiser-mot-de-passe', [AuthentificationController::class, 'reinitialiserMotDePasse']],

    // CORRIGÉ: Routes de traitement spécifiques
    ['POST', '/traitement-etudiant', [EtudiantsController::class, 'executerAction']],
    ['POST', '/traitement-enseignant', [EnseignantController::class, 'executerAction']],
    ['POST', '/traitement-personnel-admin', [PersonnelAdministratifController::class, 'executerAction']],

    // Routes pour la soumission de rapport
    ['POST', '/soumission-rapport/soumettre', [SoumissionRapportController::class, 'soumettre']],

    // Autres routes de traitement
    ['POST', '/charger-donnee-historique-utilisateur', [HistoriquePersonnelController::class, 'chargerPersonnelPourDonneeHistorique']],
    ['POST', '/charger-historique-personnel', [HistoriquePersonnelController::class, 'chargerDonneeHistoriquePersonnel']],
    ['POST', '/charger-permissions-groupe', [AttributionMenuController::class, 'chargerPermissionsGroupe']],
    ['POST', '/charger-formulaire-paramatre-specifique', [ParametreGenerauxController::class, 'chargerFormulaireParametreGeneraux']],

    // Routes pour la confirmation des rapports
    ['POST', '/confirmation-rapports/get-rapports', [\App\Controllers\MenuViews\ConfirmationRapportsController::class, 'getRapports']],
    ['POST', '/confirmation-rapports/get-etudiants', [\App\Controllers\MenuViews\ConfirmationRapportsController::class, 'getEtudiants']],
    ['POST', '/confirmation-rapports/executer-action', [\App\Controllers\MenuViews\ConfirmationRapportsController::class, 'executerAction']],

    // Routes pour les audits
    ['POST', '/audits/get-audits', [AuditsController::class, 'getAudits']],
    ['POST', '/audits/get-utilisateurs', [AuditsController::class, 'getUtilisateurs']],
    ['POST', '/audits/export-pdf', [AuditsController::class, 'exportPDF']],
    ['POST', '/audits/export-excel', [AuditsController::class, 'exportExcel']]
]);

return $routes;
