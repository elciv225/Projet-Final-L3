<?php

use App\Controllers\AdministrateurController;
use App\Controllers\AuthentificationController;
use App\Controllers\Public\AccueilController;
use App\Controllers\Gestions\PersonnelAdministratifController;

return [
    /* === Routes des pages === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],

    /* === Routes des gestion === */
    ['GET', '/gestion/personnel-administratif', [PersonnelAdministratifController::class, 'index']],

    ['GET', '/espace-administrateur', [AdministrateurController::class, 'index']],
    /* === Routes des traitement === */
    ['POST', '/authentification', [AuthentificationController::class, 'authentification']],
];
