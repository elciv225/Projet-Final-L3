<?php

use App\Controllers\AccueilController;
use App\Controllers\AuthentificationController;
use App\Controllers\UtlisateurController;
return [
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/utilisateur/{id:\d+}', [UtlisateurController::class, 'show']],
    ['GET', '/authentification', [AuthentificationController::class, 'index']],
    ['POST', '/login', [AuthentificationController::class, 'login']],
];
