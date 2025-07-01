<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\InscriptionEtudiantDAO;
use App\Dao\EtudiantDAO;
use App\Dao\AnneeAcademiqueDAO; // To filter by academic year or list available years
use System\Http\Response;

class ReglementInscriptionController extends Controller
{
    private InscriptionEtudiantDAO $inscriptionEtudiantDAO;
    private EtudiantDAO $etudiantDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;
    private \App\Dao\NiveauEtudeDAO $niveauEtudeDAO; // Added

    public function __construct()
    {
        parent::__construct();
        $this->inscriptionEtudiantDAO = new InscriptionEtudiantDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);
        $this->niveauEtudeDAO = new \App\Dao\NiveauEtudeDAO($this->pdo); // Added
    }

    public function index(): Response
    {
        // Fetch data needed for the view, e.g., for filters or dropdowns
        $anneesAcademiques = $this->anneeAcademiqueDAO->recupererTous('id', 'DESC');
        // $etudiants = $this->etudiantDAO->recupererTousAvecNomPrenom(); // Lighter list for dropdowns
        $etudiants = $this->etudiantDAO->recupererTous('utilisateur_id', 'ASC');
        $niveauxEtude = $this->niveauEtudeDAO->recupererTous('libelle', 'ASC');

        // Example: Fetch inscriptions for the current or a selected academic year
        // $anneeCourante = $this->anneeAcademiqueDAO->recupererAnneeCouranteOuPlusRecente();
        // $inscriptions = $anneeCourante ? $this->inscriptionEtudiantDAO->recupererInscriptionsAvecDetailsPaiement($anneeCourante->getId()) : [];

        $data = [
            'title' => 'Règlements des Inscriptions',
            'heading' => 'Règlements et Inscriptions',
            'content' => 'Gestion des statuts de règlement des frais d\'inscription des étudiants.',
            'anneesAcademiques' => $anneesAcademiques,
            'etudiants' => $etudiants,
            'niveauxEtude' => $niveauxEtude,
            // 'inscriptions' => $inscriptions,
        ];

        return Response::view('menu_views/reglement-inscription', $data);
    }

    /**
     * Exemple: Marquer une inscription comme payée ou partiellement payée.
     * Devrait être appelé via une requête POST.
     * @return Response
     */
    public function mettreAJourStatutPaiement(): Response
    {
        // $idInscription = $this->request->getPostParams('id_inscription');
        // $statutPaiement = $this->request->getPostParams('statut_paiement'); // e.g., 'paye', 'partiel', 'non_paye'
        // $montantPaye = $this->request->getPostParams('montant_paye');
        // $datePaiement = $this->request->getPostParams('date_paiement', date('Y-m-d'));

        // if (!$idInscription || !$statutPaiement) {
        //     return $this->error("Données manquantes pour la mise à jour du statut de paiement.");
        // }

        // $inscription = $this->inscriptionEtudiantDAO->recupererParId($idInscription);
        // if (!$inscription) {
        //     return $this->error("Inscription non trouvée.");
        // }

        // $dataToUpdate = [
        //     'statut_paiement' => $statutPaiement, // Assuming this column exists in 'inscription_etudiant'
        //     'montant_paye' => $montantPaye,       // Assuming this column exists
        //     'date_dernier_paiement' => $datePaiement // Assuming this column exists
        // ];

        // if ($this->inscriptionEtudiantDAO->mettreAJour($idInscription, $dataToUpdate)) {
        //     // Potentially log this action or send a notification
        //     return $this->succes("Statut de paiement mis à jour avec succès.");
        // } else {
        //     return $this->error("Erreur lors de la mise à jour du statut de paiement.");
        // }
        return $this->info("Fonctionnalité de mise à jour du statut de paiement à implémenter.");
    }

    /**
     * Exemple: Rechercher des étudiants ou des inscriptions par statut de paiement.
     * @return Response
     */
    public function rechercherInscriptions(): Response
    {
        // $nomEtudiant = $this->request->getPostParams('nom_etudiant');
        // $statutFiltre = $this->request->getPostParams('statut_paiement_filtre');
        // $idAnneeAcademique = $this->request->getPostParams('id_annee_academique');

        // $criteres = [];
        // if ($nomEtudiant) {
        //    // This would require a JOIN with etudiant table or a more complex query in DAO
        //    // $criteres['etudiant.nom'] = $nomEtudiant; // pseudo-code
        // }
        // if ($statutFiltre) {
        //     $criteres['inscription_etudiant.statut_paiement'] = $statutFiltre;
        // }
        // if ($idAnneeAcademique) {
        //     $criteres['inscription_etudiant.id_annee_academique'] = $idAnneeAcademique;
        // }

        // $resultats = $this->inscriptionEtudiantDAO->rechercherInscriptionsAvecDetails($criteres); // Method to be created

        // $data = [
        //     'title' => 'Résultats Recherche Règlements',
        //     'inscriptions' => $resultats,
        //     // other necessary data for the view
        // ];
        // return Response::view('menu_views/reglement-inscription', $data, json: ['statut' => 'succes']); // Or return HTML partial
        return $this->info("Fonctionnalité de recherche d'inscriptions à implémenter.");
    }
}