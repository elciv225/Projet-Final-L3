<?php
namespace App\Controllers;
use System\Http\Response;
class UtlisateurController
{
    public function show($id): Response
    {
        return Response::view('utilisateur', [
            'title' => 'Profil utilisateur',
            'heading' => 'Profil de l\'utilisateur',
            'id' => $id,
            'content' => "Voici les informations de l'utilisateur avec l'ID : $id"
        ]);
    }

}