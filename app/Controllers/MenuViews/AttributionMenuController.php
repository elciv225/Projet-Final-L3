<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\AttributionMenuDAO;
use App\Dao\GroupeUtilisateurDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class AttributionMenuController extends Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    /**
     * Affiche la page principale de gestion des attributions de menu.
     */
    public function index(): Response
    {
        $groupeDAO = new GroupeUtilisateurDAO($this->pdo);
        $attributionDAO = new AttributionMenuDAO($this->pdo);

        $menusBruts = $attributionDAO->recupererMenusAvecActions();

        // Structurer les données pour un affichage facile dans la vue
        $menusStructures = [];
        foreach ($menusBruts as $row) {
            $menusStructures[$row['menu_libelle']][$row['traitement_id']]['libelle'] = $row['traitement_libelle'];
            $menusStructures[$row['menu_libelle']][$row['traitement_id']]['actions'][$row['action_id']] = $row['action_libelle'];
        }

        $data = [
            'title' => 'Gestion des Menus et Permissions',
            'groupes' => $groupeDAO->recupererTous(),
            'menus' => $menusStructures
        ];

        return Response::view('menu_views/attribution-menu', $data);
    }

    /**
     * Gère les requêtes POST pour charger ou sauvegarder les permissions.
     */
    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? '';

        return match ($operation) {
            'charger' => $this->chargerPermissions(),
            'sauvegarder' => $this->sauvegarderPermissions(),
            default => $this->error("Action non reconnue."),
        };
    }

    /**
     * Charge les permissions pour un groupe donné et les renvoie en JSON.
     */
    private function chargerPermissions(): Response
    {
        $groupeId = $this->request->getPostParams('groupe_id');
        if (!$groupeId) {
            return Response::json(['error' => 'ID de groupe manquant'], 400);
        }

        $dao = new AttributionMenuDAO($this->pdo);
        $permissions = $dao->recupererPermissionsParGroupe($groupeId);

        // Formater pour une utilisation simple en JS
        $formattedPermissions = [];
        foreach ($permissions as $perm) {
            $formattedPermissions[$perm['traitement_id']][$perm['action_id']] = true;
        }

        return Response::json($formattedPermissions);
    }

    /**
     * Sauvegarde les nouvelles permissions envoyées depuis le client.
     */
    private function sauvegarderPermissions(): Response
    {
        $groupeId = $this->request->getPostParams('groupe_id');
        $permissionsJson = $this->request->getPostParams('permissions');

        if (!$groupeId || !$permissionsJson) {
            return $this->error("Données manquantes pour la sauvegarde.");
        }

        $permissions = json_decode($permissionsJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->error("Format de permissions invalide.");
        }

        $dao = new AttributionMenuDAO($this->pdo);
        try {
            $dao->mettreAJourPermissions($groupeId, $permissions);
            return $this->succes("Permissions mises à jour avec succès pour le groupe '{$groupeId}'.");
        } catch (\PDOException $e) {
            return $this->error("Erreur lors de la sauvegarde: " . $e->getMessage());
        }
    }
}
