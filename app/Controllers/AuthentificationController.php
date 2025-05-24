<?php

namespace App\Controllers;

use System\Http\Request;
use System\Http\Response;

class AuthentificationController extends Controller
{

    public function index(): Response
    {
        return Response::view('authentification');
    }

    public function login(): Response
    {
        $request = Request::create();

        $login = $this->request->getPostParams('login');
        $password = $this->request->getPostParams('password');

        if ($request->getMethod() === 'POST') {
            if ($login == "admin" && $password == "1234") {
                $this->succes('Connexion Réussie');
            } else {
                $this->error('Erreur de connexion');
            }
        }

        return $this->index();
    }

}