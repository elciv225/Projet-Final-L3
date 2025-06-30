<?php

namespace App\Controllers\Gestions;

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

        // Toujours retourner la vue de gestion, jamais la page complète
        return Response::view('gestion/utilisateurs', $data);
    }

    public function chargerFormulaireCategorie(): Response
    {

        $categorie = $this->request->getPostParams('categorie-utilisateur');

        if (!$categorie) {
            return Response::view('gestion/utilisateurs',
                json: [
                    'statut' => 'succes',
                    'message' => 'Aucune catégorie selectionnée.'
                ]);
        }

        $viewName = match ($categorie) {
            'etudiant' => 'gestion/etudiants',
            'enseignant' => 'gestion/personnel-universite',
            'administratif' => 'gestion/personnel-universite',
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