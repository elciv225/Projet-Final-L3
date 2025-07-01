<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EvaluationDAO;
use App\Dao\EtudiantDAO;
use App\Dao\InscriptionEtudiantDAO; // To get context like annee academique, stage, etc.
use App\Dao\StageEffectueDAO;     // If evaluations are linked to specific internships
use App\Dao\RapportEtudiantDAO;   // If evaluations are for reports
use System\Http\Response;

class EvaluationEtudiantController extends Controller
{
    private EvaluationDAO $evaluationDAO;
    private EtudiantDAO $etudiantDAO;
    private InscriptionEtudiantDAO $inscriptionEtudiantDAO;
    private \App\Dao\EcueDAO $ecueDAO; // Added
    private \App\Dao\UeDAO $ueDAO; // Added
    private \App\Dao\AnneeAcademiqueDAO $anneeAcademiqueDAO; // Added
    // private StageEffectueDAO $stageEffectueDAO;
    // private RapportEtudiantDAO $rapportEtudiantDAO;

    public function __construct()
    {
        parent::__construct();
        $this->evaluationDAO = new EvaluationDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->inscriptionEtudiantDAO = new InscriptionEtudiantDAO($this->pdo);
        $this->ecueDAO = new \App\Dao\EcueDAO($this->pdo); // Added
        $this->ueDAO = new \App\Dao\UeDAO($this->pdo); // Added
        $this->anneeAcademiqueDAO = new \App\Dao\AnneeAcademiqueDAO($this->pdo); // Added
        // $this->stageEffectueDAO = new StageEffectueDAO($this->pdo);
        // $this->rapportEtudiantDAO = new RapportEtudiantDAO($this->pdo);
    }

    public function index(): Response
    {
        // Fetch data needed for evaluation forms/lists
        // $etudiants = $this->etudiantDAO->recupererTousAvecNomPrenom(); // Custom method for lighter student list
        $etudiants = $this->etudiantDAO->recupererTous('utilisateur_id', 'ASC'); // Or generic, view needs to handle full object
        $ecues = $this->ecueDAO->recupererTous('libelle', 'ASC');
        $ues = $this->ueDAO->recupererTous('libelle', 'ASC');
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTous('id', 'DESC');
        // $evaluationsExistantes = $this->evaluationDAO->recupererToutesLesEvaluationsAvecDetails();

        $data = [
            'title' => 'Évaluation des Étudiants',
            'heading' => 'Évaluations',
            'content' => 'Interface pour gérer les évaluations des étudiants.',
            'etudiants' => $etudiants,
            'ecues' => $ecues,
            'ues' => $ues,
            'anneesAcademiques' => $anneesAcademiques,
            // 'evaluations' => $evaluationsExistantes,
        ];

        return Response::view('menu_views/evaluation-etudiant', $data);
    }

    /**
     * Exemple: Affiche le formulaire pour ajouter/modifier une évaluation pour un étudiant.
     * @return Response
     */
    public function formulaireEvaluation(): Response
    {
        // $idEtudiant = $this->request->getQueryParam('id_etudiant');
        // $idEvaluation = $this->request->getQueryParam('id_evaluation'); // For modification

        // $etudiant = null;
        // $evaluation = null;

        // if ($idEtudiant) {
        //     $etudiant = $this->etudiantDAO->recupererParId($idEtudiant);
        // }
        // if ($idEvaluation) {
        //     $evaluation = $this->evaluationDAO->recupererParId($idEvaluation);
        //     // If modifying, $idEtudiant might come from $evaluation->getIdEtudiant()
        //     // $etudiant = $this->etudiantDAO->recupererParId($evaluation->getIdEtudiant());
        // }

        // if (!$etudiant && !$idEvaluation) { // Need at least student for new eval, or eval for modification
        //     return $this->error("Étudiant ou évaluation non spécifié.");
        // }

        // $data = [
        //     'title' => $idEvaluation ? 'Modifier Évaluation' : 'Nouvelle Évaluation',
        //     'etudiant' => $etudiant,
        //     'evaluation' => $evaluation,
        //     // Potentially other data like list of criteria, etc.
        // ];
        // return Response::view('menu_views/formulaire_evaluation_etudiant', $data); // A specific view for the form
        return $this->info("Fonctionnalité de formulaire d'évaluation à implémenter.");
    }

    /**
     * Exemple: Sauvegarde une évaluation.
     * Devrait être appelé via une requête POST.
     * @return Response
     */
    public function sauvegarderEvaluation(): Response
    {
        // $idEvaluation = $this->request->getPostParams('id_evaluation'); // For update
        // $idEtudiant = $this->request->getPostParams('id_etudiant');
        // $idRapport = $this->request->getPostParams('id_rapport_etudiant'); // or id_stage_effectue
        // $note = $this->request->getPostParams('note');
        // $commentaire = $this->request->getPostParams('commentaire');
        // $dateEvaluation = date('Y-m-d');
        // $idEvaluateur = $this->request->getPostParams('id_evaluateur'); // Likely from session

        // if (!$idEtudiant || !$idRapport || !isset($note)) {
        //     return $this->error("Données manquantes pour sauvegarder l'évaluation.");
        // }

        // $dataEvaluation = [
        //     'id_rapport_etudiant' => $idRapport, // ou id_stage_effectue
        //     'id_evaluateur' => $idEvaluateur,
        //     'note' => $note,
        //     'commentaire' => $commentaire,
        //     'date_evaluation' => $dateEvaluation,
        // ];

        // if ($idEvaluation) { // Update
        //     if ($this->evaluationDAO->mettreAJour($idEvaluation, $dataEvaluation)) {
        //         return $this->succes("Évaluation mise à jour avec succès.");
        //     }
        //     return $this->error("Erreur lors de la mise à jour de l'évaluation.");
        // } else { // Create
        //     if ($this->evaluationDAO->creer($dataEvaluation)) {
        //         return $this->succes("Évaluation enregistrée avec succès.");
        //     }
        //     return $this->error("Erreur lors de l'enregistrement de l'évaluation.");
        // }
        return $this->info("Fonctionnalité de sauvegarde d'évaluation à implémenter.");
    }
}