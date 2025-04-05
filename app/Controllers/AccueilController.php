<?php

namespace App\Controllers;

use System\Http\Response;

class AccueilController
{
    public function index(): Response
    {
        return Response::view('accueil', [
            'title' => 'Accueil',
            'heading' => 'Bienvenue sur mon site',
            'content' => 'Ceci est la page d\'accueil de mon application.'
        ], ['menu']);
    }
}