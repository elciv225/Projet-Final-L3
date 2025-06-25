<?php

namespace App\Controllers;

use System\Http\Response;

class CommissionController
{
    public function index():Response
    {
        $data = [
            'title' => 'Comission de validation',
            'heading' => 'Espace Comission',
            'content' => 'Commité de validation des rapports étudiant.'
        ];

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('espace-commission', $data);
    }

}