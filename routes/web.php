<?php

use App\Controllers\AdministrateurController;
use App\Controllers\AuthentificationController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Gestions\PersonnelAdministratifController;
use App\Controllers\Gestions\EnseignantsController;
use App\Controllers\Gestions\EtudiantsController;

return [
    /* === Routes des pages === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],

    /* === Routes de l'espace administrateur === */
    ['GET', '/espace-administrateur', [AdministrateurController::class, 'index']],
    ['GET', '/espace-administrateur/gestion/personnel-administratif', [AdministrateurController::class, 'gestionPersonnelAdministratif']],
    ['GET', '/espace-administrateur/gestion/enseignants', [AdministrateurController::class, 'gestionEnseignants']],
    ['GET', '/espace-administrateur/gestion/etudiants', [AdministrateurController::class, 'gestionEtudiants']],
    /* === Routes des gestions === */
    ['GET', '/gestion/personnel-administratif', [PersonnelAdministratifController::class, 'index']],
    ['GET', '/gestion/enseignants', [EnseignantsController::class, 'index']],
    ['GET', '/gestion/etudiants', [EtudiantsController::class, 'index']],

    /* === Routes des traitements === */
    ['POST', '/authentification', [AuthentificationController::class, 'authentification']],
];