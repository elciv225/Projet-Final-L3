<?php

namespace App\Controllers\Gestions;

use System\Http\Response;

class EnseignantsController
{
   public function index(): Response
   {
      return Response::view('gestion/enseignants', [
         'title' => 'Gestion des Enseignants',
         'heading' => 'Liste des Enseignants',
         'content' => 'Ceci est la page de gestion des enseignants.'
      ]);
   }
}