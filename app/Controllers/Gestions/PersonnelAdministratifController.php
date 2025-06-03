<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class PersonnelAdministratifController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion du Personnel Administratif',
            'heading' => 'Personnel Administratif',
            'content' => 'Gestion du personnel administratif de l\'établissement.'
        ];

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/personnel-administratif', $data);
    }

    /**
     * Détecte si la requête est une requête AJAX
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}