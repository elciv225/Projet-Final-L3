<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\GroupeUtilisateurDAO;
use App\Dao\MenuDAO;
use App\Dao\CategorieMenuDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;

class AttributionMenuController extends Controller
{
    protected PDO $pdo;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;
    private MenuDAO $menuDAO;
    private CategorieMenuDAO $categorieMenuDAO;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
        $this->menuDAO = new MenuDAO($this->pdo);
        $this->categorieMenuDAO = new CategorieMenuDAO($this->pdo);
    }

    public function index(): Response
    {
        $groupes = $this->groupeUtilisateurDAO->recupererTous();
        // Récupérer tous les menus structurés par catégorie pour l'affichage initial (si nécessaire)
        // ou laisser le JS charger les permissions pour le premier groupe/aucun groupe.
        $menus = $this->menuDAO->recupererTousAvecDetails(); // Tous les menus possibles

        // Grouper les menus par catégorie pour un affichage structuré
        $menusParCategorie = [];
        foreach ($menus as $menu) {
            $categorieId = $menu['categorie_menu_id'] ?? 'SANS_CATEGORIE';
            $categorieLibelle = $menu['categorie_menu_libelle'] ?? 'Autres Menus';
            if (!isset($menusParCategorie[$categorieId])) {
                $menusParCategorie[$categorieId] = [
                    'libelle' => $categorieLibelle,
                    'menus' => []
                ];
            }
            $menusParCategorie[$categorieId]['menus'][] = $menu;
        }


        $data = [
            'title' => 'Attribution des Menus aux Groupes',
            'heading' => 'Permissions des Menus',
            'groupesUtilisateur' => $groupes,
            'menusParCategorie' => $menusParCategorie, // Tous les menus disponibles, groupés
            // Les permissions spécifiques seront chargées via AJAX ou pour le premier groupe par défaut
        ];

        return Response::view('menu_views/attribution-menu', $data);
    }

    /**
     * Récupère les permissions pour un groupe spécifique.
     * Appelée via AJAX.
     */
    public function chargerPermissionsGroupe(): Response
    {
        $groupeId = $this->request->getGetParams('groupe_id') ?? null;
        if (!$groupeId) {
            return Response::json(['error' => 'ID de groupe manquant.'], 400);
        }

        // Récupérer tous les menus possibles
        $tousLesMenus = $this->menuDAO->recupererTousAvecDetails();
        // Récupérer les menus/permissions déjà attribués à ce groupe
        $menusDuGroupe = $this->menuDAO->recupererMenusParGroupe($groupeId);

        // Transformer $menusDuGroupe en un lookup map pour un accès facile
        $permissionsLookup = [];
        foreach ($menusDuGroupe as $menuGroupe) {
            $permissionsLookup[$menuGroupe['id']] = [
                'peut_ajouter' => (bool)$menuGroupe['peut_ajouter'],
                'peut_modifier' => (bool)$menuGroupe['peut_modifier'],
                'peut_supprimer' => (bool)$menuGroupe['peut_supprimer'],
                'peut_imprimer' => (bool)$menuGroupe['peut_imprimer'],
            ];
        }

        // Préparer les données pour la vue : tous les menus, avec leurs permissions pour le groupe
        $menusAvecPermissions = [];
        foreach ($tousLesMenus as $menu) {
            $menuId = $menu['id'];
            $menu['permissions'] = $permissionsLookup[$menuId] ?? [
                'peut_ajouter' => false, 'peut_modifier' => false,
                'peut_supprimer' => false, 'peut_imprimer' => false
            ];
            $menusAvecPermissions[] = $menu;
        }

        // Grouper par catégorie pour l'affichage
        $menusParCategorie = [];
        foreach ($menusAvecPermissions as $menu) {
            $categorieId = $menu['categorie_menu_id'] ?? 'SANS_CATEGORIE';
            $categorieLibelle = $menu['categorie_menu_libelle'] ?? 'Autres Menus';
            if (!isset($menusParCategorie[$categorieId])) {
                $menusParCategorie[$categorieId] = [
                    'libelle' => $categorieLibelle,
                    'menus' => []
                ];
            }
            $menusParCategorie[$categorieId]['menus'][] = $menu;
        }


        return Response::json(['menusParCategorie' => $menusParCategorie]);
    }


    /**
     * Met à jour les permissions pour un groupe.
     * Appelée via AJAX.
     */
    public function mettreAJourPermissions(): Response
    {
        $groupeId = $this->request->getPostParams('groupe_id') ?? null;
        $permissionsData = $this->request->getPostParams('permissions') ?? null; //  JSON string '{"MENU_ID1": {"peut_ajouter": true, ...}, ...}'

        if (!$groupeId || !$permissionsData) {
            return Response::json(['error' => 'Données manquantes.'], 400);
        }

        $permissionsArray = json_decode($permissionsData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::json(['error' => 'Format JSON invalide pour les permissions.'], 400);
        }

        try {
            $this->pdo->beginTransaction();
            foreach ($permissionsArray as $menuId => $perms) {
                $this->menuDAO->mettreAJourPermissions($menuId, $groupeId, $perms);
            }
            $this->pdo->commit();
            return Response::json(['success' => true, 'message' => 'Permissions mises à jour avec succès.']);
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur PDO lors de la mise à jour des permissions: " . $e->getMessage());
            return Response::json(['error' => 'Erreur de base de données lors de la mise à jour.'], 500);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
             error_log("Erreur lors de la mise à jour des permissions: " . $e->getMessage());
            return Response::json(['error' => 'Erreur système lors de la mise à jour.'], 500);
        }
    }
}