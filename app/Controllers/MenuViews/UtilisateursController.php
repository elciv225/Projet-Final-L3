<?php

namespace App\Controllers\MenuViews;


use PDO;
use App\Controllers\Controller;
use System\Http\Response;
use App\Dao\UtilisateurDAO;

class UtilisateursController extends Controller
{
    protected PDO $pdo;

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
            'enseignant',
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

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        if (!$operation) {
            return $this->error("Aucune Action");
        }
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue"),
        };
    }

    private function traiterAjout(): Response
    {
        return $this->info("Ajouter un utilisateur");
    }

    private function traiterModification(): Response
    {
        return $this->info("Modifier un utilisateur");
    }

    private function traiterSuppression(): Response
    {
        return $this->info("Supprimer un utilisateur");
    }
}