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
use App\Controllers\MenuViews\UtilisateursController;
use App\Controllers\IndexController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Public\AuthentificationPublicController;
use App\Controllers\Public\SoumissionRapportController;

/**
 * Configuration des modules disponibles dans l'application.
 * Chaque module peut définir une méthode principale (pour l'affichage)
 * et une liste de traitements spécifiques (pour les actions POST, etc.).
 */
$configurationModules = [
    'gestion' => [
        'utilisateurs' => [
            'controleur' => UtilisateursController::class,
            'methodePrincipale' => 'index',
            'label' => 'Utilsateur',
            'icone' => '👨‍',
            'description' => 'Gestion des utilisateurs de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        'parametres-generaux' => [
            'controleur' => ParametreGenerauxController::class,
            'methodePrincipale' => 'index',
            'label' => 'Paramètres Généraux',
            'icone' => '💁🏾‍',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        'historique-personnel' => [
            'controleur' => HistoriquePersonnelController::class,
            'methodePrincipale' => 'index',
            'label' => 'Historique du Personnel',
            'icone' => '💁🏾‍',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        ],
    'autres'=>[
        'evaluation-etudiant' => [
            'controleur' => EvaluationEtudiantController::class,
            'methodePrincipale' => 'index',
            'label' => 'Évaluation Étudiants',
            'icone' => '🤧‍',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        'attribution-menu' => [
            'controleur' => AttributionMenuController::class,
            'methodePrincipale' => 'index',
            'label' => 'Gestion des menus',
            'icone' => '💁🏾‍♂️',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        'reglement-inscription' => [
            'controleur' => ReglementInscriptionController::class,
            'methodePrincipale' => 'index',
            'label' => 'Reglement Inscription',
            'icone' => '💲',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        ],
    'commission'=>[
        'messagerie-commission' => [
            'controleur' => DiscussionController::class,
            'methodePrincipale' => 'index',
            'label' => 'Discussion',
            'icone' => '😎',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
        'historique-approbation' => [
            'controleur' => HistoriqueApprobationController::class,
            'methodePrincipale' => 'index',
            'label' => 'Historique des approbations',
            'icone' => '💁🏾‍',
            'description' => 'Gestion des étudiants de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel étudiant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un étudiant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un étudiant',
                ]
            ]
        ],
    ]
];

// Stockage global de la configuration pour y accéder dans les contrôleurs.
// Cela garantit que la configuration est disponible partout sans la passer explicitement.
if (!defined('MODULES_CONFIG')) {
    define('MODULES_CONFIG', $configurationModules);
}

/**
 * Définition des routes de l'application.
 * Chaque route est un tableau: [méthode HTTP, chemin, [Contrôleur::class, 'méthode']].
 */
$routes = [
    /* === Routes des pages publiques === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/authentification', [AuthentificationPublicController::class, 'index']],
    ['GET', '/authentification-administration', [AuthentificationController::class, 'index']],
    ['GET', '/soumission-rapport', [SoumissionRapportController::class, 'index']],
    ['GET', '/test-animations', [AccueilController::class, 'testAnimations']],
    ['GET', '/espace-commission', [CommissionController::class, 'index']],
    ['GET', '/espace-commission/commission/discussion', [DiscussionController::class, 'index']],

    /* === Routes de l'espace administrateur === */
    ['GET', '/index', [IndexController::class, 'index']],
];

/**
 * Génération automatique des routes pour les modules et leurs traitements.
 * Cela permet de ne pas avoir à écrire chaque route manuellement.
 */
foreach ($configurationModules as $categorie => $modulesParCategorie) {
    foreach ($modulesParCategorie as $nomModule => $configurationModule) {
        /* === Routes des menus de l'administrateur (GET) === */
        $routes[] = ['GET', "/index/$categorie/$nomModule", [IndexController::class, 'gestionMenuModules']];

        // Ajout des routes pour les traitements spécifiques du module (POST, etc.)
        if (isset($configurationModule['traitements'])) {
            foreach ($configurationModule['traitements'] as $nomTraitement => $configTraitement) {
                $routes[] = [
                    $configTraitement['methodeHttp'],
                    "/index/$categorie/$nomModule/$nomTraitement/",
                    [IndexController::class, 'gererTraitementModule']
                ];

                // Route directe pour API
                if (class_exists($configurationModule['controleur'])) {
                    $routes[] = [
                        $configTraitement['methodeHttp'],
                        "/$categorie/$nomModule/$nomTraitement",
                        [$configurationModule['controleur'], $nomTraitement]
                    ];
                }
            }
        }
    }
}

// Ajouter les routes de traitement des formulaires (authentification, par exemple)
$routes = array_merge($routes, [
    /* === Routes des traitements (formulaires) === */
    ['POST', '/authentification', [AuthentificationPublicController::class, 'authentification']],
    ['POST', '/authentification-administration', [AuthentificationController::class, 'authentification']],
    ['POST', '/charger-formulaire-categorie', [UtilisateursController::class, 'chargerFormulaireCategorie']],
    ['POST', '/charger-formulaire-paramatre-specifique', [ParametreGenerauxController::class, 'chargerFormulaireCategorie']],
    ['POST', '/traitement-utilisateur', [UtilisateursController::class, 'executerAction']],
    ['POST', '/charger-donnee-historique-utilisateur', [HistoriquePersonnelController::class, 'chargerPersonnelPourDonneeHistorique']],
    ['POST', '/charger-historique-personnel', [HistoriquePersonnelController::class, 'chargerDonneeHistoriquePersonnel']],
]);


return $routes;