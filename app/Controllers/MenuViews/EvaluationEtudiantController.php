<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\AnneeAcademiqueDAO;
use App\Dao\EcueDAO;
use App\Dao\EnseignantDAO; // Pour lister les enseignants
use App\Dao\EtudiantDAO;   // Pour lister les étudiants
use App\Dao\EvaluationDAO;
use App\Dao\SessionExamenDAO; // Supposant l'existence de ce DAO
use System\Database\Database;
use System\Http\Response;
use PDO;
use App\Traits\ValidationTrait;

class EvaluationEtudiantController extends Controller
{
    use ValidationTrait;

    // protected PDO $pdo; // Parent
    private EvaluationDAO $evaluationDAO;
    private EtudiantDAO $etudiantDAO;
    private EnseignantDAO $enseignantDAO;
    private EcueDAO $ecueDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;
    private SessionExamenDAO $sessionExamenDAO;


    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->evaluationDAO = new EvaluationDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->enseignantDAO = new EnseignantDAO($this->pdo);
        $this->ecueDAO = new EcueDAO($this->pdo);
        $this->anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);
        $this->sessionExamenDAO = new SessionExamenDAO($this->pdo); // Initialiser
    }

    public function index(): Response
    {
        $evaluations = $this->evaluationDAO->recupererTousAvecDetails();
        $etudiants = $this->etudiantDAO->recupererTous(['id', 'nom', 'prenoms']); // Pour les selects
        $enseignants = $this->enseignantDAO->recupererTous(['id', 'nom', 'prenoms']); // Pour les selects
        $ecues = $this->ecueDAO->recupererTous(['id', 'libelle']); // Pour les selects
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTousTriesParIdDesc();
        $sessionsExamen = $this->sessionExamenDAO->recupererTous();


        $data = [
            'title' => 'Évaluation des Étudiants',
            'heading' => 'Évaluation des Étudiants',
            'evaluations' => $evaluations,
            'etudiants' => $etudiants,
            'enseignants' => $enseignants,
            'ecues' => $ecues,
            'anneesAcademiques' => $anneesAcademiques,
            'sessionsExamen' => $sessionsExamen,
        ];
        return Response::view('menu_views/evaluation-etudiant', $data);
    }

    private function indexMessage(string $message, string $statut = "info"): Response
    {
        // Recharger toutes les données nécessaires pour la vue, y compris les listes pour les formulaires.
        $evaluations = $this->evaluationDAO->recupererTousAvecDetails();
        $etudiants = $this->etudiantDAO->recupererTous(['id', 'nom', 'prenoms']);
        $enseignants = $this->enseignantDAO->recupererTous(['id', 'nom', 'prenoms']);
        $ecues = $this->ecueDAO->recupererTous(['id', 'libelle']);
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTousTriesParIdDesc();
        $sessionsExamen = $this->sessionExamenDAO->recupererTous();

        // Données complètes pour la vue principale
        $viewData = [
            'title' => 'Évaluation des Étudiants',
            'heading' => 'Évaluation des Étudiants',
            'evaluations' => $evaluations,
            'etudiants' => $etudiants,
            'enseignants' => $enseignants,
            'ecues' => $ecues,
            'anneesAcademiques' => $anneesAcademiques,
            'sessionsExamen' => $sessionsExamen,
        ];

        // Données pour la vue partielle AJAX (uniquement la liste des évaluations)
        // et pour le JSON si aucune vue partielle AJAX n'est spécifiée.
        $partialViewData = ['evaluations' => $evaluations];

        return $this->reponseVueAvecMessage(
            'menu_views/evaluation-etudiant', // Vue complète
            $viewData,
            $message,
            $statut,
            'partials/table-evaluations-rows', // Vue partielle pour AJAX
            $partialViewData
        );
    }


    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        $data = $this->request->getPostParams();

        // Les clés composites sont utilisées pour identifier une évaluation unique.
        // Elles doivent être présentes pour 'modifier' et 'supprimer'.
        // Pour 'ajouter', elles font partie des données à insérer.
        $keys = [
            'enseignant_id' => $data['id_enseignant_original'] ?? ($data['enseignant_id'] ?? null),
            'etudiant_id' => $data['id_etudiant_original'] ?? ($data['etudiant_id'] ?? null),
            'ecue_id' => $data['id_ecue_original'] ?? ($data['ecue_id'] ?? null),
            'annee_academique_id' => $data['annee_academique_id_original'] ?? ($data['annee_academique_id'] ?? null),
            'session_id' => $data['session_id_original'] ?? ($data['session_id'] ?? null),
        ];

        // Données pour l'insertion ou la mise à jour
        $evaluationData = [
            'enseignant_id' => $data['enseignant_id'] ?? null,
            'etudiant_id' => $data['etudiant_id'] ?? null,
            'ecue_id' => $data['ecue_id'] ?? null,
            'annee_academique_id' => $data['annee_academique_id'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'date_evaluation' => $data['date_evaluation'] ?? null,
            'note' => $data['note'] ?? null,
        ];


        // Validation des champs requis pour ajout/modification
        if ($operation === 'ajouter' || $operation === 'modifier') {
            $rules = [
                'enseignant_id' => 'required',
                'etudiant_id' => 'required',
                'ecue_id' => 'required',
                'annee_academique_id' => 'required',
                'session_id' => 'required',
                'date_evaluation' => 'required|date',
                'note' => 'required|numeric|min:0|max:20'
            ];
            // Pour la modification, les clés originales sont aussi requises
            if ($operation === 'modifier') {
                $rules['id_enseignant_original'] = 'required';
                $rules['id_etudiant_original'] = 'required';
                $rules['id_ecue_original'] = 'required';
                $rules['annee_academique_id_original'] = 'required';
                $rules['session_id_original'] = 'required';
            }

            if (!$this->validate($data, $rules)) { // Valider $data qui contient tous les champs du formulaire
                return $this->indexMessage($this->getAllErrorsAsString(), "error");
            }
        }


        try {
            switch ($operation) {
                case 'ajouter':
                    // $evaluationData a déjà été construit à partir de $data
                    if ($this->evaluationDAO->recupererParCleComposite($evaluationData)) {
                         return $this->indexMessage("Une évaluation pour cet étudiant, cet ECUE, cette année et cette session existe déjà.", "error");
                    }
                    if ($this->evaluationDAO->creerEvaluation($evaluationData)) {
                        return $this->indexMessage("Évaluation ajoutée avec succès.", "succes");
                    }
                    return $this->indexMessage("Erreur lors de l'ajout de l'évaluation.", "error");

                case 'modifier':
                    // $keys contient les clés originales, $evaluationData les nouvelles valeurs (y compris potentiellement les mêmes clés)
                    // Si les clés primaires ne peuvent pas changer, on utilise $keys pour le WHERE et certaines parties de $evaluationData pour le SET.
                    // La méthode mettreAJourNote du DAO est spécifique, si d'autres champs que note/date peuvent changer, il faut une méthode plus générique.
                    // Pour cet exemple, on met à jour note et date_evaluation en utilisant les clés originales.
                    $updateDataForDAO = [
                        'enseignant_id' => $keys['enseignant_id'], // Clé originale
                        'etudiant_id' => $keys['etudiant_id'],     // Clé originale
                        'ecue_id' => $keys['ecue_id'],         // Clé originale
                        'annee_academique_id' => $keys['annee_academique_id'], // Clé originale
                        'session_id' => $keys['session_id'],       // Clé originale
                        'note' => $evaluationData['note'],                   // Nouvelle note
                        'date_evaluation' => $evaluationData['date_evaluation'] // Nouvelle date
                    ];
                    if ($this->evaluationDAO->mettreAJourNote($updateDataForDAO)) {
                        return $this->indexMessage("Évaluation mise à jour avec succès.", "succes");
                    }
                    return $this->indexMessage("Erreur lors de la mise à jour ou aucune modification détectée.", "info");

                case 'supprimer':
                    $ids = $this->request->getPostParams('ids'); // Tableau de chaînes JSON des clés composites
                    if (empty($ids) || !is_array($ids)) {
                        return $this->indexMessage("Aucune évaluation sélectionnée pour la suppression.", "error");
                    }
                    $deletedCount = 0;
                    foreach ($ids as $jsonKey) {
                        $keyArray = json_decode($jsonKey, true);
                        if ($keyArray && $this->evaluationDAO->supprimerEvaluation($keyArray)) {
                            $deletedCount++;
                        }
                    }
                    if ($deletedCount > 0) {
                        return $this->indexMessage("$deletedCount évaluation(s) supprimée(s) avec succès.", "succes");
                    }
                    return $this->indexMessage("Aucune évaluation n'a pu être supprimée.", "error");

                default:
                    return $this->indexMessage("Action non reconnue.", "error");
            }
        } catch (PDOException $e) {
            error_log("Erreur PDO dans EvaluationEtudiantController: " . $e->getMessage());
             if ($e->getCode() == '23000') { // Violation de contrainte (ex: clé dupliquée)
                 return $this->indexMessage("Erreur de base de données : L'évaluation existe déjà ou une contrainte a été violée.", "error");
            }
            return $this->indexMessage("Erreur de base de données.", "error");
        } catch (\Exception $e) {
            error_log("Erreur système dans EvaluationEtudiantController: " . $e->getMessage());
            return $this->indexMessage("Une erreur système est survenue.", "error");
        }
    }
}