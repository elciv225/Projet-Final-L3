<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class UeController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

// Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/ue', $data);
    }
}