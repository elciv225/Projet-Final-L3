<?php
namespace App\Controllers;
use System\Http\Response;
class UtlisateurController
{
    public function show($id): Response
    {
        return new Response("Utilisateur avec l'ID : $id");
    }

}