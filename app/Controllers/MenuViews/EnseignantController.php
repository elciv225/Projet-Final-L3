<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EnseignantDAO;
use App\Dao\UtilisateurDAO; // Pour la suppression
use App\Dao\GradeDAO;
use App\Dao\SpecialiteDAO;
use App\Dao\FonctionDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;
use PDOException;

class EnseignantController extends Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    /**
     * Affiche la page principale de gestion des enseignants.
     */
    public function index(): Response
    {
        $enseignantDAO = new EnseignantDAO($this->pdo);
        $gradeDAO = new GradeDAO($this->pdo);
        $specialiteDAO = new SpecialiteDAO($this->pdo);
        $fonctionDAO = new FonctionDAO($this->pdo);

        $data = [
            'title' => 'Gestion des Enseignants',
            'enseignants' => $enseignantDAO->recupererTousAvecDetails(),
            'grades' => $gradeDAO->recupererTous(),
            'specialites' => $specialiteDAO->recupererTous(),
            'fonctions' => $fonctionDAO->recupererTous(),
        ];

        // Assurez-vous d'avoir une vue 'menu_views/enseignants.php'
        return Response::view('menu_views/enseignants', $data);
    }

    /**
     * Point d'entrée pour les actions POST.
     */
    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue pour les enseignants."),
        };
    }

    /**
     * Traite l'ajout d'un nouvel enseignant.
     */
    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();

        try {
            $enseignantDAO = new EnseignantDAO($this->pdo);
            $params = [
                'nom' => $post['nom-enseignant'],
                'prenoms' => $post['prenom-enseignant'],
                'email' => $post['email-enseignant'],
                'mot_de_passe' => 'password123', // Mot de passe par défaut
                'date_naissance' => $post['date-naissance'],
                'grade_id' => $post['id-grade'] ?? null,
                'specialite_id' => $post['id-specialite'] ?? null,
                'fonction_id' => $post['id-fonction'] ?? null,
            ];

            $userId = $enseignantDAO->creerViaProcedure($params);
            if (!$userId) {
                throw new \Exception("La création de l'enseignant a échoué.");
            }

            return $this->indexMessage("Enseignant '{$userId}' ajouté avec succès.", "succes");

        } catch (\PDOException $e) {
            return $this->error("Erreur PDO: " . $e->getMessage());
        } catch (\Exception $e) {
            return $this->error("Erreur système: " . $e->getMessage());
        }
    }

    /**
     * Traite la modification d'un enseignant.
     */
    private function traiterModification(): Response
    {
        $post = $this->request->getPostParams();
        $userId = $post['id-utilisateur'] ?? null;

        if (!$userId) {
            return $this->error("ID de l'enseignant manquant pour la modification.");
        }

        try {
            $enseignantDAO = new EnseignantDAO($this->pdo);
            $params = [
                'id_utilisateur' => $userId,
                'nom' => $post['nom-enseignant'],
                'prenoms' => $post['prenom-enseignant'],
                'email' => $post['email-enseignant'],
                'date_naissance' => $post['date-naissance'],
                'grade_id' => $post['id-grade'] ?? null,
                'specialite_id' => $post['id-specialite'] ?? null,
                'fonction_id' => $post['id-fonction'] ?? null,
            ];

            $enseignantDAO->modifierViaProcedure($params);

            return $this->indexMessage("Enseignant '{$userId}' mis à jour avec succès.", "succes");

        } catch (\PDOException $e) {
            return $this->error("Erreur PDO lors de la modification: " . $e->getMessage());
        }
    }

    /**
     * Traite la suppression d'enseignants.
     */
    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->error("Aucun ID sélectionné pour la suppression.");
        }

        try {
            // La procédure sp_supprimer_personnel est générique et peut être dans UtilisateurDAO
            $utilisateurDAO = new UtilisateurDAO($this->pdo);
            $deletedCount = $utilisateurDAO->supprimerPersonnel($ids);

            return $this->indexMessage("$deletedCount enseignant(s) supprimé(s).", "succes");

        } catch (\PDOException $e) {
            return $this->error("Erreur lors de la suppression: " . $e->getMessage());
        }
    }

    /**
     * Retourne la vue partielle de la table pour la mise à jour AJAX.
     */
    private function indexMessage(string $message, string $statut = "info"): Response
    {
        $enseignantDAO = new EnseignantDAO($this->pdo);
        $data = ['enseignants' => $enseignantDAO->recupererTousAvecDetails()];

        // Assurez-vous d'avoir une vue partielle `_table-enseignants.php`
        return Response::view('menu_views/enseignants', $data, json: [
            'statut' => $statut,
            'message' => $message
        ]);
    }
}
