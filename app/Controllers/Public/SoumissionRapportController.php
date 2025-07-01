<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use App\Dao\RapportEtudiantDAO;
use App\Dao\DepotRapportDAO;
use App\Dao\EtudiantDAO; // To link submission to an etudiant
use App\Dao\AnneeAcademiqueDAO; // To get current academic year for submission context
use System\Http\Response;
// Potentially use Mail class for notifications
// use System\Mail\Mail;

class SoumissionRapportController extends Controller
{
    private RapportEtudiantDAO $rapportEtudiantDAO;
    private DepotRapportDAO $depotRapportDAO;
    private EtudiantDAO $etudiantDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;
    private \App\Dao\UeDAO $ueDAO; // Added
    // private Mail $mail;

    public function __construct()
    {
        parent::__construct();
        $this->rapportEtudiantDAO = new RapportEtudiantDAO($this->pdo);
        $this->depotRapportDAO = new DepotRapportDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);
        $this->ueDAO = new \App\Dao\UeDAO($this->pdo); // Added
        // $this->mail = Mail::make($configMail); // Configure mail if needed
    }

    /**
     * Affiche la page du formulaire de soumission de rapport.
     */
    public function index(): Response
    {
        // Fetch data needed for the form
        // $idEtudiantConnecte = $_SESSION['etudiant_id'] ?? null; // Example: Get logged-in student ID from session
        // $etudiant = $idEtudiantConnecte ? $this->etudiantDAO->recupererParId($idEtudiantConnecte) : null;

        // Attempt to get current academic year. Needs a specific method in AnneeAcademiqueDAO.
        // $anneeCourante = $this->anneeAcademiqueDAO->recupererAnneeCouranteOuPlusRecente();
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTous('id', 'DESC');
        $anneeCourante = !empty($anneesAcademiques) ? $anneesAcademiques[0] : null;


        $ues = $this->ueDAO->recupererTous('libelle', 'ASC');

        $data = [
            'title' => 'Soumission de Rapport',
            'heading' => 'Soumettre un Rapport',
            'content' => 'Formulaire de soumission de rapport.',
            // 'etudiant' => $etudiant, // Pass student object if available
            'anneeCourante' => $anneeCourante, // Pass current academic year object
            'ues' => $ues, // Pass list of UEs for a dropdown
        ];
        return Response::view('public/soumission-rapport', $data);
    }

    /**
     * Traite la soumission du formulaire de rapport.
     * Devrait être appelé via une requête POST.
     * @return Response
     */
    public function soumettre(): Response
    {
        // $idEtudiant = $this->request->getPostParams('id_etudiant'); // From session or hidden form field
        // $titreRapport = $this->request->getPostParams('titre_rapport');
        // $resumeRapport = $this->request->getPostParams('resume_rapport');
        // $idUe = $this->request->getPostParams('id_ue'); // If applicable
        // $idAnneeAcademique = $this->request->getPostParams('id_annee_academique');
        // $fichierRapport = $this->request->getFiles('fichier_rapport'); // Handle file upload

        // if (!$idEtudiant || !$titreRapport || !$fichierRapport || $fichierRapport['error'] !== UPLOAD_ERR_OK) {
        //     return $this->error("Données manquantes ou erreur lors de l'upload du fichier.");
        // }

        // // 1. Handle file upload - move to a secure location, generate unique name
        // $uploadDir = '/path/to/rapports/';
        // $nomFichier = uniqid('rapport_', true) . '_' . basename($fichierRapport['name']);
        // $cheminFichier = $uploadDir . $nomFichier;
        // if (!move_uploaded_file($fichierRapport['tmp_name'], $cheminFichier)) {
        //     return $this->error("Erreur lors de la sauvegarde du fichier uploadé.");
        // }

        // $this->pdo->beginTransaction();
        // try {
        //     // 2. Create RapportEtudiant entry
        //     $dataRapport = [
        //         'id_etudiant' => $idEtudiant,
        //         'id_ue' => $idUe, // nullable if not applicable
        //         'id_annee_academique' => $idAnneeAcademique,
        //         'titre_rapport' => $titreRapport,
        //         'resume' => $resumeRapport,
        //         'statut_approbation' => 'soumis', // Initial status
        //         'date_creation' => date('Y-m-d H:i:s'),
        //     ];
        //     if (!$this->rapportEtudiantDAO->creer($dataRapport)) {
        //         throw new \Exception("Erreur lors de la création de l'entrée rapport.");
        //     }
        //     $idRapportEtudiant = $this->pdo->lastInsertId();

        //     // 3. Create DepotRapport entry
        //     $dataDepot = [
        //         'id_rapport_etudiant' => $idRapportEtudiant,
        //         'chemin_fichier' => $cheminFichier, // Path relative to a base or absolute
        //         'nom_fichier_original' => basename($fichierRapport['name']),
        //         'type_fichier' => $fichierRapport['type'],
        //         'taille_fichier' => $fichierRapport['size'],
        //         'date_depot' => date('Y-m-d H:i:s'),
        //         'version' => 1, // First submission
        //     ];
        //     if (!$this->depotRapportDAO->creer($dataDepot)) {
        //         throw new \Exception("Erreur lors de la création de l'entrée dépôt.");
        //     }

        //     $this->pdo->commit();

        //     // Optionally send email notification to student and/or admin
        //     // $this->mail->to($etudiant->getEmail())->subject('Confirmation de soumission')->body('Votre rapport a été soumis...')->send();

        //     return $this->succes("Rapport soumis avec succès. ID Rapport: $idRapportEtudiant", redirect: '/confirmation-soumission'); // Redirect to a confirmation page
        // } catch (\Exception $e) {
        //     $this->pdo->rollBack();
        //     // If transaction failed, delete uploaded file if it exists
        //     if (isset($cheminFichier) && file_exists($cheminFichier)) {
        //         unlink($cheminFichier);
        //     }
        //     return $this->error("Erreur lors de la soumission du rapport: " . $e->getMessage());
        // }
        return $this->info("Fonctionnalité de soumission de rapport à implémenter.");
    }
}