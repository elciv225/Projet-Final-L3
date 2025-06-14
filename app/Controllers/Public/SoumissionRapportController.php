<?php

namespace App\Controllers\Public;

use System\Http\Response;

class SoumissionRapportController
{
    /**
     * Page de soumission de rapport
     */
    public function index(): Response
    {


        // Toujours retourner la vue de soumission, jamais la page complète
        return Response::view('public/soumission-rapport', $data = [
            'title' => 'Soumission de Rapport',
            'heading' => 'Soumettre un Rapport',
            'content' => 'Formulaire de soumission de rapport.'
        ]);
    }

}