<?php

namespace App\Controllers;

use System\Http\Response;

class AdministrateurController
{
    public function index(): Response
    {
        return Response::view('espace-administrateur', [
            'title' => 'Espace Administrateur',
            'heading' => 'Bienvenue dans l\'Espace Administrateur',
            'content' => 'Ceci est la page d\'accueil de l\'espace administrateur.'
        ], ['menu']);
    }

}