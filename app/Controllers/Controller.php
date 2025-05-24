<?php

namespace App\Controllers;

use System\Http\Request;
use System\Http\Response;

class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::create();
    }


    /**
     * Fonction de pop up erreur
     * @param string $message
     * @return Response JSON pour la pop up
     */
    public function succes(string $message):Response{
        return Response::json([
            'statut' => 'succes',
            'message' => $message
        ]);
    }

    /**
     * Fonction de pop up erreur
     * @param string $message
     * @return Response JSON pour la pop up
     */
    public function error(string $message):Response{
        return Response::json([
            'statut' => 'error',
            'message' => $message
        ]);
    }
}
