<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use System\Http\Response;

class UtilisateursController extends Controller
{
    public function index(): Response
    {

        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.',
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/utilisateurs', $data);
    }

    public function chargerFormulaireCategorie(): Response
    {

        $categorie = $this->request->getPostParams('categorie-utilisateur');

        if (!$categorie) {
            return Response::view('menu_views/utilisateurs',
                json: [
                    'statut' => 'succes',
                    'message' => 'Aucune catégorie selectionnée.'
                ]);
        }

        $viewName = match ($categorie) {
            'etudiant' => 'data_views/etudiants',
            'enseignant' => 'data_views/personnel-universite',
            'administratif' => 'data_views/personnel-universite',
        };

        return Response::view(
            view: $viewName,
            data: ['categorieUtilisateur' => ucfirst($categorie)],
            json: [
                'statut' => 'succes',
            ]
        );
    }
}