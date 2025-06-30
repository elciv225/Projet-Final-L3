<?php

namespace App\Controllers\MenuViews;

use System\Http\Response;

class ReglementInscriptionController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

// Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/reglement-inscription', $data);
    }
}