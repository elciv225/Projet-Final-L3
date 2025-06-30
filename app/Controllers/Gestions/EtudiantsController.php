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
        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

// Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/etudiants', $data);
    }

}