<?php

namespace App\Controllers;

use System\Http\Response;

class AuthentificationController
{
    public function index(): Response
    {
        return Response::view('authentification', [
            'title' => 'Authentification',
        ]);
    }

    public function authentification(): Response
    {
        return Response::redirect('/index');
       // return Response::success('Connexion réussie' ,redirect: '/index');
    }
}