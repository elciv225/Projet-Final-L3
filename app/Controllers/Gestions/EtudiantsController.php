<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class EtudiantsController
{
    /**
     * Affiche la page de gestion des étudiants.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Response::view('gestions/etudiants/index', [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Liste des Étudiants',
            'content' => 'Ceci est la page de gestion des étudiants.'
        ]);
    }
}