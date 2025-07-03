<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\ConfirmationRapportDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class ConfirmationRapportsController extends Controller
{
    protected PDO $pdo;
    protected ConfirmationRapportDAO $confirmationDAO;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->confirmationDAO = new ConfirmationRapportDAO($this->pdo);
    }

    /**
     * Affiche la page principale de confirmation des rapports
     */
    public function index(): Response
    {
        $data = [
            'title' => 'Confirmation des Rapports',
            'heading' => 'Confirmation des Rapports de Stage',
            'content' => 'Gestion et confirmation des rapports de stage déposés par les étudiants.'
        ];

        return Response::view('menu_views/confirmation-rapports', $data);
    }

    /**
     * Récupère les rapports avec pagination et filtrage (AJAX)
     */
    public function getRapports(): Response
    {
        // Récupérer les paramètres de pagination et de filtrage
        $page = (int)($this->request->getPostParams('page') ?? 1);
        $limit = (int)($this->request->getPostParams('limit') ?? 10);
        
        $filtres = [
            'statut' => $this->request->getPostParams('statut'),
            'etudiant_id' => $this->request->getPostParams('etudiant_id'),
            'date_debut' => $this->request->getPostParams('date_debut'),
            'date_fin' => $this->request->getPostParams('date_fin')
        ];
        
        // Filtrer les valeurs vides
        $filtres = array_filter($filtres);
        
        // Récupérer les rapports
        $result = $this->confirmationDAO->getRapportsWithPagination($page, $limit, $filtres);
        
        return Response::json([
            'statut' => 'succes',
            'data' => $result
        ]);
    }

    /**
     * Récupère la liste des étudiants ayant déposé un rapport (AJAX)
     */
    public function getEtudiants(): Response
    {
        $etudiants = $this->confirmationDAO->getEtudiantsAvecRapport();
        
        return Response::json([
            'statut' => 'succes',
            'data' => $etudiants
        ]);
    }

    /**
     * Exécute une action sur un ou plusieurs rapports (AJAX)
     */
    public function executerAction(): Response
    {
        $action = $this->request->getPostParams('action');
        $rapportIds = $this->request->getPostParams('rapport_ids');
        $commentaire = $this->request->getPostParams('commentaire') ?? '';
        
        if (empty($action) || empty($rapportIds)) {
            return Response::json([
                'statut' => 'error',
                'message' => 'Paramètres manquants'
            ], 400);
        }
        
        // Convertir en tableau si ce n'est pas déjà le cas
        if (!is_array($rapportIds)) {
            $rapportIds = json_decode($rapportIds, true);
        }
        
        // Implémenter les actions spécifiques ici
        // Pour l'instant, on retourne juste un succès
        return Response::json([
            'statut' => 'succes',
            'message' => 'Action exécutée avec succès',
            'action' => $action,
            'rapport_ids' => $rapportIds
        ]);
    }
}