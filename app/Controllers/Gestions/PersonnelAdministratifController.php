<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class PersonnelAdministratifController
{
    public function index(): Response
    {
        return Response::view('gestion/personnel-administratif', [
            'title' => 'Gestion du Personnel Administratif',
            'heading' => 'Liste du Personnel Administratif',
            'content' => 'Ceci est la page de gestion du personnel administratif.'
        ]);
    }
}