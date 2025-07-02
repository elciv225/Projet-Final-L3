<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\PersonnelAdministratifDAO;
use App\Dao\TypeUtilisateurDAO;
use App\Dao\UtilisateurDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class PersonnelAdministratifController extends Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    public function index(): Response
    {
        $personnelDAO = new PersonnelAdministratifDAO($this->pdo);
        $typeUtilisateurDAO = new TypeUtilisateurDAO($this->pdo);

        $data = [
            'title' => 'Gestion du Personnel Administratif',
            'personnels' => $personnelDAO->recupererTousAvecDetails(),
            // Filtre pour ne récupérer que les types de la catégorie 'CAT_ADMIN'
            'typesUtilisateur' => $typeUtilisateurDAO->recupererParCategorie('CAT_ADMIN'),
        ];

        return Response::view('menu_views/personnel-administratif', $data);
    }

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue."),
        };
    }

    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();
        try {
            $dao = new UtilisateurDAO($this->pdo);
            $params = [
                'nom' => $post['nom-personnel'],
                'prenoms' => $post['prenom-personnel'],
                'email' => $post['email-personnel'],
                'mot_de_passe' => 'password123',
                'date_naissance' => $post['date-naissance'],
            ];
            $userId = $dao->creerPersonnelAdminViaProcedure($params);
            return $this->indexMessage("Personnel '{$userId}' ajouté avec succès.", "succes");
        } catch (\PDOException $e) {
            return $this->error("Erreur PDO: " . $e->getMessage());
        }
    }

    private function traiterModification(): Response
    {
        $post = $this->request->getPostParams();
        $userId = $post['id-utilisateur'] ?? null;
        if (!$userId) return $this->error("ID manquant.");

        try {
            $dao = new PersonnelAdministratifDAO($this->pdo);
            $params = [
                'id_utilisateur' => $userId,
                'nom' => $post['nom-personnel'],
                'prenoms' => $post['prenom-personnel'],
                'email' => $post['email-personnel'],
                'date_naissance' => $post['date-naissance'],
            ];
            $dao->modifierViaProcedure($params);
            return $this->indexMessage("Personnel '{$userId}' mis à jour.", "succes");
        } catch (\PDOException $e) {
            return $this->error("Erreur PDO: " . $e->getMessage());
        }
    }

    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids)) return $this->error("Aucun ID sélectionné.");

        try {
            $dao = new UtilisateurDAO($this->pdo);
            $count = $dao->supprimerPersonnel($ids);
            return $this->indexMessage("$count membre(s) du personnel supprimé(s).", "succes");
        } catch (\PDOException $e) {
            return $this->error("Erreur lors de la suppression: " . $e->getMessage());
        }
    }

    private function indexMessage(string $message, string $statut = "info"): Response
    {
        $dao = new PersonnelAdministratifDAO($this->pdo);
        $data = ['personnels' => $dao->recupererTousAvecDetails()];
        return Response::view('menu_views/personnel-administratif', $data, json: [
            'statut' => $statut,
            'message' => $message
        ]);
    }
}
