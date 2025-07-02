<?php

namespace App\Controllers\Commission;

use System\Http\Response;

class HistoriqueApprobationController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Historique des approbations',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/historique-approbation', $data);
    }
}