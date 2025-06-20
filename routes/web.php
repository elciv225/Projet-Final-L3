<?php

use App\Controllers\AdministrateurController;
use App\Controllers\AuthentificationPublicController;
use App\Controllers\Gestions\EnseignantsController;
use App\Controllers\Gestions\EtudiantsController;
use App\Controllers\Gestions\PersonnelAdministratifController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Public\SoumissionRapportController;

/**
 * Configuration des modules disponibles dans l'application.
 * Chaque module peut définir une méthode principale (pour l'affichage)
 * et une liste de traitements spécifiques (pour les actions POST, etc.).
 */
$configurationModules = [
    'gestion' => [
        'personnel-administratif' => [
            'controleur' => PersonnelAdministratifController::class,
            'methodePrincipale' => 'index',
            'label' => 'Personnel Administratif',
            'icone' => '👨‍💼',
            'description' => 'Gestion du personnel administratif de l\'établissement',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel élément de personnel administratif'
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier un élément de personnel administratif existant'
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un élément de personnel administratif',
                    // 'action' => 'supprimerPersonnel'
                ]
            ]
        ],
        'enseignants' => [
            'controleur' => EnseignantsController::class,
            'methodePrincipale' => 'index',
            'label' => 'Enseignants',
            'icone' => '👨‍🏫', // Correction: L'icône était incomplète '👨‍'
            'description' => 'Gestion du corps enseignant',
            'traitements' => [
                'ajouter' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Ajouter un nouvel enseignant',
                ],
                'modifier' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Modifier les informations d\'un enseignant existant',
                ],
                'supprimer' => [
                    'methodeHttp' => 'POST',
                    'description' => 'Supprimer un enseignant',
                ]
            ]
        ],
        'etudiants' => [
            'controleur' => EtudiantsController::class,
            'methodePrincipale' => 'index',
            'label' => 'Étudiants',
            'icone' => '👨‍',
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
        ]
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
    ['GET', '/soumission-rapport', [SoumissionRapportController::class, 'index']],

    /* === Routes de l'espace administrateur === */
    ['GET', '/espace-administrateur', [AdministrateurController::class, 'index']],
];

/**
 * Génération automatique des routes pour les modules et leurs traitements.
 * Cela permet de ne pas avoir à écrire chaque route manuellement.
 */
foreach ($configurationModules as $categorie => $modulesParCategorie) {
    foreach ($modulesParCategorie as $nomModule => $configurationModule) {
        /* === Routes des menus de l'administrateur (GET) === */
        $routes[] = [
            'GET',
            "/espace-administrateur/$categorie/$nomModule",
            [AdministrateurController::class, 'gestionMenuModules']
        ];

        // Ajout des routes pour les traitements spécifiques du module (POST, etc.)
        if (isset($configurationModule['traitements'])) {
            foreach ($configurationModule['traitements'] as $nomTraitement => $configTraitement) {
                $routes[] = [
                    $configTraitement['methodeHttp'],
                    "/espace-administrateur/$categorie/$nomModule/$nomTraitement/",
                    [AdministrateurController::class, 'gererTraitementModule']
                ];

                // Route directe pour API
                if (class_exists($configurationModule['controleur'])) {
                    $routes[] = [
                        $configTraitement['methodeHttp'],
                        "/$categorie/$nomModule/$nomTraitement",
                        [$configurationModule['controleur'],  $nomTraitement]
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
]);


return $routes;