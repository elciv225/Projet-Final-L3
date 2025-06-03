<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class EnseignantsController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Enseignants',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/enseignants', $data);
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}