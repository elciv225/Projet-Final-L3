<?php

namespace App\Controllers;

use System\Http\Request;
use System\Http\Response;

class AuthentificationController
{

    public function index(): Response
    {
        return Response::view('authentification');
    }

    public function login(): Response
    {

        $request = Request::create();

        $login = $request->getPostParams('login');
        $password = $request->getPostParams('password');

        if ($request->getMethod() === 'POST') {
            if ($login == "admin" && $password == "1234") {
                return Response::json([
                    'statut' => 'succes',
                    'message' => 'Connexion Réussie'
                ]);
            } else {
                return Response::json([
                    'statut' => 'error',
                    'message' => 'Erreur de connexion'
                ]);
            }
        }

        return $this->index();
    }

}