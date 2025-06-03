<?php

use App\Controllers\AdministrateurController;
use App\Controllers\AuthentificationController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Gestions\PersonnelAdministratifController;
use App\Controllers\Gestions\EnseignantsController;
use App\Controllers\Gestions\EtudiantsController;

return [
    /* === Routes des pages publiques === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],

    /* === Routes de l'espace administrateur === */
    // Route principale de l'espace admin
    ['GET', '/espace-administrateur', [AdministrateurController::class, 'index']],

    // Routes des sections de gestion (via l'espace admin)
    ['GET', '/espace-administrateur/gestion/personnel-administratif', [AdministrateurController::class, 'gestionPersonnelAdministratif']],
    ['GET', '/espace-administrateur/gestion/enseignants', [AdministrateurController::class, 'gestionEnseignants']],
    ['GET', '/espace-administrateur/gestion/etudiants', [AdministrateurController::class, 'gestionEtudiants']],

    /* === Routes directes des gestions (pour compatibilité) === */
    ['GET', '/gestion/personnel-administratif', [PersonnelAdministratifController::class, 'index']],
    ['GET', '/gestion/enseignants', [EnseignantsController::class, 'index']],
    ['GET', '/gestion/etudiants', [EtudiantsController::class, 'index']],

    /* === Routes des traitements (formulaires) === */
    ['POST', '/authentification', [AuthentificationController::class, 'authentification']],

    /* === Routes API pour AJAX (optionnelles pour plus tard) === */
    // ['GET', '/api/stats', [AdministrateurController::class, 'getStats']],
    // ['POST', '/api/personnel', [PersonnelAdministratifController::class, 'store']],
    // ['PUT', '/api/personnel/{id}', [PersonnelAdministratifController::class, 'update']],
    // ['DELETE', '/api/personnel/{id}', [PersonnelAdministratifController::class, 'delete']],
];