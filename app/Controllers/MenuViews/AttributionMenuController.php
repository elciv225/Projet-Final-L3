<?php

namespace App\Controllers\MenuViews;

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

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/attribution-menu', $data);
    }
}