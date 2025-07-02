<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EnseignantDAO;
use App\Dao\PersonnelAdministratifDAO;
use App\Dao\HistoriqueFonctionDAO;
use App\Dao\HistoriqueGradeDAO;
use App\Dao\HistoriqueSpecialiteDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class HistoriquePersonnelController extends Controller
{
    private PDO $pdo;
    private EnseignantDAO $enseignantDAO;
    private PersonnelAdministratifDAO $personnelDAO; // Peut être utilisé pour récupérer nom/prénom

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->enseignantDAO = new EnseignantDAO($this->pdo);
        $this->personnelDAO = new PersonnelAdministratifDAO($this->pdo); // ou UtilisateurDAO
    }

    public function index(): Response
    {
        $data = [
            'title' => 'Historique du Personnel',
            'heading' => 'Historique du Personnel',
            // Les listes déroulantes seront initialement vides ou avec un placeholder
        ];
        return Response::view('menu_views/historique-personnel', $data);
    }

    /**
     * Charge la liste des personnels (enseignants ou administratifs) pour le sélecteur.
     * Appelée via AJAX.
     */
    public function chargerPersonnels(): Response
    {
        $typeUtilisateur = $this->request->getGetParams('type_utilisateur');
        $personnels = [];

        if ($typeUtilisateur === 'enseignant') {
            // Récupérer uniquement id, nom, prenoms pour alléger
            $personnels = $this->enseignantDAO->recupererTous(['id', 'nom', 'prenoms']);
        } elseif ($typeUtilisateur === 'personnel_administratif') {
            // Idem pour le personnel administratif
            $personnels = $this->personnelDAO->recupererTous(['id', 'nom', 'prenoms']);
        } else {
            return Response::json(['error' => 'Type d\'utilisateur non valide.'], 400);
        }
        return Response::json(['personnels' => $personnels]);
    }

    /**
     * Charge et affiche l'historique demandé pour un personnel.
     * Appelée via AJAX par le formulaire de filtres.
     */
    public function afficherHistorique(): Response
    {
        $typeHistorique = $this->request->getPostParams('type-historique');
        $utilisateurId = $this->request->getPostParams('utilisateur');
        // Le type d'utilisateur (enseignant/admin) est implicite par la sélection de l'utilisateur,
        // mais on pourrait le re-valider ou le passer si nécessaire.

        if (empty($typeHistorique) || empty($utilisateurId)) {
            // Normalement, le JS empêche cette soumission, mais double vérification.
            // Renvoyer une vue partielle vide ou avec un message d'erreur.
             return Response::view('partials/table-historique-vide', ['message' => 'Veuillez sélectionner tous les filtres.']);
        }

        $entete = [];
        $lignes = [];
        $nomPersonnel = "Inconnu"; // À récupérer

        // Récupérer le nom du personnel pour l'affichage
        // On a besoin de savoir si c'est un enseignant ou un admin pour utiliser le bon DAO,
        // ou d'avoir un UtilisateurDAO générique.
        // Pour simplifier, on essaie les deux ou on se base sur une convention d'ID.
        // Supposons qu'on a un UtilisateurDAO:
        $userDao = new \App\Dao\UtilisateurDAO($this->pdo);
        $personnelInfo = $userDao->recupererParId($utilisateurId);
        if ($personnelInfo) {
            $nomPersonnel = $personnelInfo->getNom() . " " . $personnelInfo->getPrenoms();
        }


        switch ($typeHistorique) {
            case 'fonction':
                $dao = new HistoriqueFonctionDAO($this->pdo);
                // La méthode recupererAvecDetailsPourUtilisateur doit joindre avec la table 'fonction'
                $lignes = $dao->recupererAvecDetailsPourUtilisateur($utilisateurId);
                $entete = ['Fonction', 'Date d\'Occupation']; // + Date de Fin si gérée
                break;
            case 'grade':
                $dao = new HistoriqueGradeDAO($this->pdo);
                $lignes = $dao->recupererAvecDetailsPourUtilisateur($utilisateurId);
                $entete = ['Grade', 'Date d\'Obtention']; // + Document si géré
                break;
            case 'specialite': // Uniquement pour enseignants
                $dao = new HistoriqueSpecialiteDAO($this->pdo);
                $lignes = $dao->recupererAvecDetailsPourUtilisateur($utilisateurId);
                $entete = ['Spécialité', 'Date d\'Affectation'];
                break;
            default:
                 return Response::view('partials/table-historique-vide', ['message' => 'Type d\'historique non reconnu.']);
        }

        $dataPourVuePartielle = [
            'enteteTable' => $entete,
            'lignesTable' => $lignes,
            'typeHistoriqueDemandee' => $typeHistorique,
            'nomPersonnel' => $nomPersonnel,
            'utilisateurId' => $utilisateurId,
            'donneesChargees' => true
        ];

        // Renvoyer la vue partielle de la table (et potentiellement du formulaire de modification si on l'ajoute)
        // Le target du formulaire AJAX est '.table', donc on renvoie juste le contenu de la table.
        // Si on veut mettre à jour plus, il faudrait une cible plus large.
        return Response::view('partials/table-historique-contenu', $dataPourVuePartielle);
    }

    // TODO: Ajouter des méthodes pour gérer l'ajout, la modification, la suppression
    // d'entrées d'historique si cela est prévu depuis cette interface.
    // Actuellement, la maquette se concentre sur la consultation.
}