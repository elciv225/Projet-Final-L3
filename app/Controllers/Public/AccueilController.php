<?php

namespace App\Controllers\Public;

use System\Http\Response;

class AccueilController
{
    public function index(): Response
    {
        return Response::view('public/accueil', [
            'title' => 'Accueil',
            'heading' => 'Bienvenue sur mon site',
            'content' => 'Ceci est la page d\'accueil de mon application.'
        ]);
    }

    public function testAnimations(): Response{
        return Response::view('public/test-animations', [
            'title' => 'Test Animations',
            'heading' => 'Test des animations CSS',
            'content' => 'Cette page est dédiée aux tests d\'animations CSS.'
        ]);
    }
}