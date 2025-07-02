<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use System\Http\Response;

class ParametreGenerauxController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Paramètres Generaux',
            'heading' => 'Étudiants',
            'content' => 'Gestion des étudiants de l\'établissement.'
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/parametres-generaux', $data);
    }

    public function chargerFormulaireParametreGeneraux(): Response
    {

        $paramatre = $this->request->getPostParams('parametre-specifique');

        if (!$paramatre) {
            return Response::view('menu_views/utilisateurs',
                json: [
                    'statut' => 'succes',
                    'message' => 'Aucune catégorie selectionnée.'
                ]);
        }

        $viewName = match ($paramatre) {
            'ue',
            'ecue' => 'data_views/ue',
            'annee_academique' => 'data_views/annee_academique',
            'specialite',
            'menu',
            'categorie_menu',
            'entreprise',
            'niveau_etude',
            'grade',
            'fonction',
            'categorie_utilisateur',
            'groupe_utilisateur',
            'niveau_acces_donnees',
            'statut_jury',
            'niveau_approbation',
            'traitement',
            'action' => 'data_views/parametre-general',

            // Un cas par défaut est prévu pour gérer les valeurs inattendues ou vides.
            default => 'errors/404'

        };

        return Response::view(
            view: $viewName,
            data: ['parametre' => ucfirst($paramatre)],
            json: [
                'statut' => 'succes',
            ]
        );
    }

}