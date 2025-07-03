<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EvaluationDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class EvaluationEtudiantController extends Controller
{
    protected PDO $pdo;
    protected EvaluationDAO $evaluationDAO;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->evaluationDAO = new EvaluationDAO($this->pdo);
    }

    public function index(): Response
    {
        // Récupérer les données nécessaires pour la vue
        $evaluations = $this->evaluationDAO->recupererToutesAvecDetails();
        $enseignants = $this->evaluationDAO->recupererEnseignants();
        $etudiants = $this->evaluationDAO->recupererEtudiants();
        $ecues = $this->evaluationDAO->recupererEcues();

        $data = [
            'title' => 'Évaluation des Étudiants',
            'heading' => 'Évaluation des Étudiants',
            'content' => 'Gestion des évaluations des étudiants.',
            'evaluations' => $evaluations,
            'enseignants' => $enseignants,
            'etudiants' => $etudiants,
            'ecues' => $ecues
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/evaluation-etudiant', $data);
    }

    /**
     * Traite l'ajout d'une nouvelle évaluation
     * @return Response
     */
    public function ajouterEvaluation(): Response
    {
        $post = $this->request->getPostParams();

        // Validation des données
        $requiredFields = ['enseignant_id', 'etudiant_id', 'ecue_id', 'date_evaluation', 'note'];
        foreach ($requiredFields as $field) {
            if (empty($post[$field])) {
                return $this->error("Le champ '$field' est obligatoire.");
            }
        }

        // Validation de la note (entre 0 et 20)
        $note = (int)$post['note'];
        if ($note < 0 || $note > 20) {
            return $this->error("La note doit être comprise entre 0 et 20.");
        }

        try {
            $success = $this->evaluationDAO->ajouterEvaluation([
                'enseignant_id' => $post['enseignant_id'],
                'etudiant_id' => $post['etudiant_id'],
                'ecue_id' => $post['ecue_id'],
                'date_evaluation' => $post['date_evaluation'],
                'note' => $note
            ]);

            if ($success) {
                // Récupérer les données mises à jour
                $evaluations = $this->evaluationDAO->recupererToutesAvecDetails();
                $enseignants = $this->evaluationDAO->recupererEnseignants();
                $etudiants = $this->evaluationDAO->recupererEtudiants();
                $ecues = $this->evaluationDAO->recupererEcues();

                return Response::view('menu_views/evaluation-etudiant', [
                    'title' => 'Évaluation des Étudiants',
                    'heading' => 'Évaluation des Étudiants',
                    'evaluations' => $evaluations,
                    'enseignants' => $enseignants,
                    'etudiants' => $etudiants,
                    'ecues' => $ecues
                ], json: [
                    'statut' => 'succes',
                    'message' => 'Évaluation ajoutée avec succès.'
                ]);
            } else {
                return $this->error("Erreur lors de l'ajout de l'évaluation.");
            }
        } catch (\PDOException $e) {
            return $this->error("Erreur de base de données: " . $e->getMessage());
        } catch (\Exception $e) {
            return $this->error("Erreur: " . $e->getMessage());
        }
    }

    // Utilise la méthode error() de la classe parente Controller
}
