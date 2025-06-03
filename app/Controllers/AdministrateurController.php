<?php

namespace App\Controllers;

use System\Http\Response;

class AdministrateurController
{

    public function index():Response
    {
        return Response::view('administrateur', [
            'title' => 'Administrateur',
            'heading' => 'Bienvenue dans l\'espace administrateur',
            'content' => 'Ceci est la page d\'administration de mon application.'
        ],layouts: ['menu']);
    }
}