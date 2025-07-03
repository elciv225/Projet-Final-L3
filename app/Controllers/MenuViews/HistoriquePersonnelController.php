<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\HistoriquePersonnelDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class HistoriquePersonnelController extends Controller
{
    protected PDO $pdo;
    protected HistoriquePersonnelDAO $historiqueDAO;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->historiqueDAO = new HistoriquePersonnelDAO($this->pdo);
    }

    public function index(): Response
    {
        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.'
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/historique-personnel', $data);
    }

    public function chargerPersonnelPourDonneeHistorique(): Response
    {
        $typeUtilisateur = $this->request->getPostParams('type-utilisateur');
        $listeUtilisateur = [];

        switch ($typeUtilisateur) {
            case 'enseignant':
                $listeUtilisateur = $this->historiqueDAO->recupererEnseignants();
                break;
            case 'personnel_administratif':
                $listeUtilisateur = $this->historiqueDAO->recupererPersonnelAdministratif();
                break;
            default:
                return $this->error("Aucun type d'utilisateur sélectionné");
        }

        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.',
            'listeUtilisateur' => $listeUtilisateur,
            'selectActive' => true,
            'recherche' => true
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view(
            'menu_views/historique-personnel',
            $data,
            json: [
                'statut' => 'succes',
                'message' => 'Données chargées avec succès'
            ]
        );
    }

    public function chargerDonneeHistoriquePersonnel(): Response
    {
        $typeHistorique = $this->request->getPostParams('type-historique');
        $utilisateurId = $this->request->getPostParams('utilisateur');
        $page = (int)($this->request->getPostParams('page') ?? 1);
        $itemsPerPage = (int)($this->request->getPostParams('items_per_page') ?? 10);

        // Valider les entrées
        if (empty($typeHistorique) || empty($utilisateurId)) {
            return Response::json([
                'statut' => 'error',
                'message' => 'Veuillez remplir tous les champs'
            ]);
        }

        $entete = [];
        $corps = [];
        $donneesChargees = true; // Indicateur que des données ont été recherchées
        $totalItems = 0;
        $totalPages = 0;

        // Récupérer les données en fonction du type d'historique
        switch ($typeHistorique) {
            case 'fonction':
                $entete = ['Fonction', 'Date de début', 'Date de fin'];
                $historiqueData = $this->historiqueDAO->recupererHistoriqueFonctions($utilisateurId, $page, $itemsPerPage);
                $totalItems = $this->historiqueDAO->compterHistoriqueFonctions($utilisateurId);

                foreach ($historiqueData as $row) {
                    $corps[] = [$row['fonction'], $row['date_debut'], $row['date_fin']];
                }
                break;

            case 'grade':
                $entete = ['Grade', 'Date d\'obtention', 'Document'];
                $historiqueData = $this->historiqueDAO->recupererHistoriqueGrades($utilisateurId, $page, $itemsPerPage);
                $totalItems = $this->historiqueDAO->compterHistoriqueGrades($utilisateurId);

                foreach ($historiqueData as $row) {
                    $corps[] = [$row['grade'], $row['date_debut'], $row['document']];
                }
                break;

            default:
                return Response::json([
                    'statut' => 'error',
                    'message' => 'Type d\'historique non reconnu'
                ]);
        }

        // Calculer le nombre total de pages
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Calculer les indices de début et de fin pour l'affichage
        $startIndex = ($page - 1) * $itemsPerPage + 1;
        $endIndex = min($startIndex + $itemsPerPage - 1, $totalItems);

        // Enregistrer l'audit
        \App\Models\Audit::enregistrer(
            "Consultation de l'historique $typeHistorique pour l'utilisateur $utilisateurId (page $page)",
            'CONSULTATION_HISTORIQUE',
            null
        );

        $data = [
            'title' => 'Gestion des Historiques',
            'heading' => 'Enseignants',
            'content' => 'Gestion du corps enseignant de l\'établissement.',
            'entete' => $entete,
            'corps' => $corps,
            'donneesChargees' => $donneesChargees,
            'pagination' => [
                'page' => $page,
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalItems,
                'totalPages' => $totalPages,
                'startIndex' => $startIndex,
                'endIndex' => $endIndex
            ]
        ];

        return Response::view('menu_views/historique-personnel', $data, json: [
            'statut' => 'succes',
            'message' => 'Données chargées avec succès'
        ]);
    }
}
