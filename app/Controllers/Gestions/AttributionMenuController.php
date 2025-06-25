<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class AttributionMenuController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Enseignants',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/attribution-menu', $data);
    }
}