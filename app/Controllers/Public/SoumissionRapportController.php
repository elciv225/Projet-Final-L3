<?php

namespace App\Controllers\Public;

use App\Controllers\Controller;
use App\Dao\DepotRapportDAO;
use App\Dao\RapportEtudiantDAO;
use App\Models\RapportEtudiant;
use System\Http\Response;

class SoumissionRapportController extends Controller
{
    private RapportEtudiantDAO $rapportDAO;
    private DepotRapportDAO $depotDAO;

    public function __construct()
    {
        parent::__construct();
        $this->rapportDAO = new RapportEtudiantDAO($this->pdo);
        $this->depotDAO = new DepotRapportDAO($this->pdo);
    }

    /**
     * Page de soumission de rapport
     */
    public function index(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Vérifier si l'utilisateur a déjà soumis un rapport
        $rapportExistant = $this->rapportDAO->getRapportEtudiant($utilisateur['id']);
        $statutRapport = null;

        if ($rapportExistant) {
            $statutRapport = $this->rapportDAO->getStatutRapport($rapportExistant->getId());
        }

        // Toujours retourner la vue de soumission, jamais la page complète
        return Response::view('public/soumission-rapport', [
            'title' => 'Soumission de Rapport',
            'heading' => 'Soumettre un Rapport',
            'content' => 'Formulaire de soumission de rapport.',
            'utilisateur' => $utilisateur,
            'rapport_existant' => $rapportExistant,
            'statut_rapport' => $statutRapport
        ]);
    }

    /**
     * Traite la soumission d'un rapport
     */
    public function soumettre(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte'])) {
            return Response::redirect('/authentification');
        }

        $utilisateur = $_SESSION['utilisateur_connecte'];

        // Récupérer les données du formulaire
        $titre = $this->request->getPostParams('report-title');
        $contenuHtml = $this->request->getPostParams('report-html');

        if (empty($titre) || empty($contenuHtml)) {
            return $this->error("Le titre et le contenu du rapport sont obligatoires");
        }

        // Vérifier si l'utilisateur a déjà soumis un rapport
        if ($this->depotDAO->aDejaDepose($utilisateur['id'])) {
            return $this->error("Vous avez déjà soumis un rapport");
        }

        // Générer un ID unique pour le rapport
        $rapportId = 'RAPPORT_' . date('Y') . '_' . $utilisateur['id'];

        // Créer le dossier etats/ s'il n'existe pas
        if (!file_exists(dirname(__DIR__, 3) . '/etats')) {
            mkdir(dirname(__DIR__, 3) . '/etats', 0777, true);
        }

        // Générer le nom du fichier HTML
        $nomFichier = $utilisateur['id'] . '_' . date('Ymd_His') . '.html';
        $cheminFichier = dirname(__DIR__, 3) . '/etats/' . $nomFichier;

        // Enregistrer le contenu HTML dans un fichier
        file_put_contents($cheminFichier, $contenuHtml);

        // Créer un nouvel objet RapportEtudiant
        $rapport = new RapportEtudiant();
        $rapport->setId($rapportId);
        $rapport->setTitre($titre);
        $rapport->setDateRapport(date('Y-m-d'));
        $rapport->setLienRapport($nomFichier);

        // Enregistrer le rapport dans la base de données
        if (!$this->rapportDAO->persister($rapport)) {
            return $this->error("Erreur lors de l'enregistrement du rapport");
        }

        // Enregistrer le dépôt du rapport
        if (!$this->depotDAO->enregistrerDepot($utilisateur['id'], $rapportId, date('Y-m-d'))) {
            return $this->error("Erreur lors de l'enregistrement du dépôt");
        }

        return Response::success("Votre rapport a été soumis avec succès", redirect: '/espace-utilisateur');
    }
}
