<?php

namespace App\Controllers\Commission;

use System\Http\Response;

class DiscussionController
{
    /**
     * Affiche la page de gestion des étudiants
     * @return Response
     */
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion',
            'heading' => 'GOAT',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('commission/discussions', $data);
    }

}