<?php

use App\Controllers\AccueilController;
use App\Controllers\AuthentificationController;
use App\Controllers\UtlisateurController;
return [
    /* === Routes des pages === */
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/utilisateur/{id:\d+}', [UtlisateurController::class, 'show']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],
    /* === Routes des traitement === */
    ['POST', '/login', [AuthentificationController::class, 'login']],
];
