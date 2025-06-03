<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class PersonnelAdministratifController
{

    public function index():Response
    {
        // Logique pour afficher la liste du personnel administratif
        return Response::view('gestion/personnel-administratif');
    }

}