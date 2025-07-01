<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\MenuDAO;
use App\Dao\AccesTraitementDAO; // For managing access to menu items/treatments
use App\Dao\GroupeUtilisateurDAO; // For assigning menus to user groups
use System\Http\Response;

class AttributionMenuController extends Controller
{
    private MenuDAO $menuDAO;
    private AccesTraitementDAO $accesTraitementDAO;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;
    private \App\Dao\TraitementDAO $traitementDAO; // Added
    private \App\Dao\ActionDAO $actionDAO; // Added
    private \App\Dao\TraitementActionDAO $traitementActionDAO; // Added

    public function __construct()
    {
        parent::__construct();
        $this->menuDAO = new MenuDAO($this->pdo);
        $this->accesTraitementDAO = new AccesTraitementDAO($this->pdo);
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
        $this->traitementDAO = new \App\Dao\TraitementDAO($this->pdo); // Added
        $this->actionDAO = new \App\Dao\ActionDAO($this->pdo); // Added
        $this->traitementActionDAO = new \App\Dao\TraitementActionDAO($this->pdo); // Added
    }

    public function index(): Response
    {
        $groupes = $this->groupeUtilisateurDAO->recupererTous('libelle', 'ASC');
        $traitements = $this->traitementDAO->recupererTous('libelle', 'ASC');
        $actions = $this->actionDAO->recupererTous('libelle', 'ASC'); // All possible actions

        // Get all links between traitements and actions
        $traitementActionsBrut = $this->traitementActionDAO->recupererTous();
        $traitementActions = [];
        foreach ($traitementActionsBrut as $ta) {
            $traitementActions[$ta->getTraitementId()][] = $ta->getActionId();
        }

        // Fetch current permissions
        $attributionsActuellesBrut = $this->accesTraitementDAO->recupererTous();
        $attributionsActuelles = [];
        foreach($attributionsActuellesBrut as $aa) {
            $attributionsActuelles[$aa->getGroupeUtilisateurId()][$aa->getTraitementId()][$aa->getActionId()] = true;
        }

        $data = [
            'title' => 'Gestion des Permissions',
            'heading' => 'Configuration des Permissions de Groupe',
            'content' => 'Interface pour attribuer les actions des traitements aux groupes d\'utilisateurs.',
            'groupes' => $groupes,
            'traitements' => $traitements,
            'actions' => $actions, // Pass all actions for reference (e.g. table headers)
            'traitementActions' => $traitementActions, // Which actions belong to which traitement : [traitement_id => [action_id1, action_id2]]
            'attributions' => $attributionsActuelles, // [groupe_id => [traitement_id => [action_id => true]]]
        ];

        return Response::view('menu_views/attribution-menu', $data);
    }

    /**
     * Exemple: Sauvegarder les attributions de menu.
     * Devrait être appelé via une requête POST.
     * @return Response
     */
    public function sauvegarderAttributions(): Response
    {
        // $donneesAttributions = $this->request->getPostParams('attributions'); // Expects array like ['id_groupe' => [id_menu1, id_menu2, ...]]

        // if (empty($donneesAttributions)) {
        //     return $this->error("Aucune donnée d'attribution reçue.");
        // }

        // $this->pdo->beginTransaction(); // Start transaction for multiple operations
        // try {
        //     // Potentially clear existing attributions for affected groups first
        //     // foreach (array_keys($donneesAttributions) as $idGroupe) {
        //     //    $this->accesTraitementDAO->supprimerAttributionsParGroupe($idGroupe); // Method to be created
        //     // }

        //     foreach ($donneesAttributions as $idGroupe => $idsMenu) {
        //         foreach ($idsMenu as $idMenu) {
        //             // This assumes AccesTraitementDAO handles linking menus (or more granularly, traitements) to groupes.
        //             // The exact structure of what is being saved (menu vs traitement) needs clarification.
        //             // For now, let's assume we are linking menus directly to a group for simplicity.
        //             // A more complex scenario involves linking specific "traitements" of a menu.

        //             // Example: if Menu has Traitements and AccesTraitement links Traitement to Groupe.
        //             // $traitementsDuMenu = $this->menuTraitementDAO->recupererParMenu($idMenu);
        //             // foreach($traitementsDuMenu as $traitement) {
        //             //    $this->accesTraitementDAO->creer(['id_groupe_utilisateur' => $idGroupe, 'id_traitement' => $traitement->getId()]);
        //             // }
        //             $this->accesTraitementDAO->creer([
        //                 'id_groupe_utilisateur' => $idGroupe,
        //                 'id_menu' => $idMenu, // This might need adjustment if AccesTraitement links to 'id_traitement'
        //                                         // or if a different table handles menu-group links.
        //                 'peut_lire' => true // Example permission
        //             ]);
        //         }
        //     }
        //     $this->pdo->commit();
        //     return $this->succes("Attributions sauvegardées avec succès.");
        // } catch (\Exception $e) {
        //     $this->pdo->rollBack();
        //     return $this->error("Erreur lors de la sauvegarde des attributions: " . $e->getMessage());
        // }
        return $this->info("Fonctionnalité de sauvegarde des attributions à implémenter.");
    }
}