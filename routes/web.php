<?php

use App\Controllers\AccueilController;
use App\Controllers\UtlisateurController;
return [
    ['GET', '/', [AccueilController::class, 'index']],
    ['GET', '/utilisateur/{id:\d+}', [UtlisateurController::class, 'show']]
];
