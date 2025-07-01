<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\ActionDAO;
use App\Dao\AnneeAcademiqueDAO;
use App\Dao\CategorieUtilisateurDAO;
use App\Dao\EcueDAO;
use App\Dao\EntrepriseDAO;
use App\Dao\FonctionDAO;
use App\Dao\GradeDAO;
use App\Dao\GroupeUtilisateurDAO;
use App\Dao\MenuDAO;
use App\Dao\NiveauAccesDonneesDAO;
use App\Dao\NiveauApprobationDAO;
use App\Dao\NiveauEtudeDAO;
use App\Dao\StatutJuryDAO;
use App\Dao\TraitementDAO;
use App\Dao\UeDAO;
use System\Http\Response;

class ParametreGenerauxController extends Controller
{
    private UeDAO $ueDAO;
    private EcueDAO $ecueDAO;
    private AnneeAcademiqueDAO $anneeAcademiqueDAO;
    private EntrepriseDAO $entrepriseDAO;
    private NiveauEtudeDAO $niveauEtudeDAO;
    private GradeDAO $gradeDAO;
    private FonctionDAO $fonctionDAO;
    private CategorieUtilisateurDAO $categorieUtilisateurDAO;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;
    private NiveauAccesDonneesDAO $niveauAccesDonneesDAO;
    private StatutJuryDAO $statutJuryDAO;
    private NiveauApprobationDAO $niveauApprobationDAO;
    private TraitementDAO $traitementDAO;
    private ActionDAO $actionDAO;
    private MenuDAO $menuDAO;
    private \App\Dao\SpecialiteDAO $specialiteDAO; // Added
    private \App\Dao\CategorieMenuDAO $categorieMenuDAO; // Added

    public function __construct()
    {
        parent::__construct();
        $this->ueDAO = new UeDAO($this->pdo);
        $this->ecueDAO = new EcueDAO($this->pdo);
        $this->anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);
        $this->entrepriseDAO = new EntrepriseDAO($this->pdo);
        $this->niveauEtudeDAO = new NiveauEtudeDAO($this->pdo);
        $this->gradeDAO = new GradeDAO($this->pdo);
        $this->fonctionDAO = new FonctionDAO($this->pdo);
        $this->categorieUtilisateurDAO = new CategorieUtilisateurDAO($this->pdo);
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
        $this->niveauAccesDonneesDAO = new NiveauAccesDonneesDAO($this->pdo);
        $this->statutJuryDAO = new StatutJuryDAO($this->pdo);
        $this->niveauApprobationDAO = new NiveauApprobationDAO($this->pdo);
        $this->traitementDAO = new TraitementDAO($this->pdo);
        $this->actionDAO = new ActionDAO($this->pdo);
        $this->menuDAO = new MenuDAO($this->pdo);
        $this->specialiteDAO = new \App\Dao\SpecialiteDAO($this->pdo); // Added
        $this->categorieMenuDAO = new \App\Dao\CategorieMenuDAO($this->pdo); // Added
    }

    public function index(): Response
    {
        $data = [
            'title' => 'Paramètres Généraux',
            'heading' => 'Paramètres Généraux',
            'content' => 'Gestion des paramètres généraux de l\'application.'
        ];
        return Response::view('menu_views/parametres-generaux', $data);
    }

    public function chargerFormulaireParametreGeneraux(): Response
    {
        $parametre = $this->request->getPostParams('parametre-specifique');
        $viewData = ['parametre_label' => ucfirst(str_replace('_', ' ', $parametre))];
        $listeDonnees = [];

        if (!$parametre) {
            return $this->error('Aucun paramètre spécifique sélectionné.');
        }

        // Charger les données pour le paramètre spécifique
        switch ($parametre) {
            case 'ue':
                $listeDonnees = $this->ueDAO->recupererTous('nom_ue', 'ASC');
                break;
            case 'ecue':
                $listeDonnees = $this->ecueDAO->recupererTousAvecNomUe(); // Méthode à créer dans EcueDAO
                $viewData['ues'] = $this->ueDAO->recupererTous('nom_ue', 'ASC'); // For dropdown in form
                break;
            case 'annee_academique':
                $listeDonnees = $this->anneeAcademiqueDAO->recupererTous('annee_debut', 'DESC');
                break;
            case 'entreprise':
                $listeDonnees = $this->entrepriseDAO->recupererTous('nom_entreprise', 'ASC');
                break;
            case 'niveau_etude':
                $listeDonnees = $this->niveauEtudeDAO->recupererTous('libelle_niveau_etude', 'ASC');
                break;
            case 'grade':
                $listeDonnees = $this->gradeDAO->recupererTous('libelle_grade', 'ASC');
                break;
            case 'fonction':
                $listeDonnees = $this->fonctionDAO->recupererTous('libelle_fonction', 'ASC');
                break;
            case 'categorie_utilisateur':
                $listeDonnees = $this->categorieUtilisateurDAO->recupererTous('libelle_categorie_utilisateur', 'ASC');
                break;
            case 'groupe_utilisateur':
                $listeDonnees = $this->groupeUtilisateurDAO->recupererTous('nom_groupe', 'ASC');
                break;
            case 'niveau_acces_donnees':
                $listeDonnees = $this->niveauAccesDonneesDAO->recupererTous('libelle_niveau_acces', 'ASC');
                break;
            case 'statut_jury':
                $listeDonnees = $this->statutJuryDAO->recupererTous('libelle_statut_jury', 'ASC');
                break;
            case 'niveau_approbation':
                $listeDonnees = $this->niveauApprobationDAO->recupererTous('libelle_niveau_approbation', 'ASC');
                break;
            case 'traitement':
                $listeDonnees = $this->traitementDAO->recupererTous('nom_traitement', 'ASC');
                break;
            case 'action':
                $listeDonnees = $this->actionDAO->recupererTous('nom_action', 'ASC');
                break;
            case 'menu':
                $listeDonnees = $this->menuDAO->recupererTous('libelle', 'ASC'); // Assuming 'libelle' for menu
                $viewData['categories_menu'] = $this->categorieMenuDAO->recupererTous('libelle', 'ASC'); // For dropdown in form
                break;
            case 'specialite': // Added case
                $listeDonnees = $this->specialiteDAO->recupererTous('libelle', 'ASC');
                break;
            case 'categorie_menu': // Added case
                $listeDonnees = $this->categorieMenuDAO->recupererTous('libelle', 'ASC');
                break;
            // 'specialite', 'categorie_menu' need their own DAOs if they are distinct tables
            // For now, they might fall into the generic 'parametre-general' view without specific data loading here
            // or require new DAOs e.g. SpecialiteDAO, CategorieMenuDAO
        }

        $viewData['liste_donnees'] = $listeDonnees;
        $viewData['parametre_type'] = $parametre; // Pass the type for conditional rendering in the view

        $viewName = match ($parametre) {
            'ue', 'ecue' => 'data_views/ue', // This view might need to handle both UE and ECUE logic
            'annee_academique' => 'data_views/annee_academique', // Specific view for annee academique
            'specialite', // Needs SpecialiteDAO
            'menu', // Needs MenuDAO, CategorieMenuDAO
            'categorie_menu', // Needs CategorieMenuDAO
            'entreprise',
            'niveau_etude',
            'grade',
            'fonction',
            'categorie_utilisateur',
            'groupe_utilisateur',
            'niveau_acces_donnees',
            'statut_jury',
            'niveau_approbation',
            'traitement',
            'action' => 'data_views/parametre-general', // Generic view for simple parameters
            default => 'errors/404'
        };

        // If the view name is 'errors/404', it means the parameter wasn't matched for data loading or specific view.
        if ($viewName === 'errors/404' && $parametre) {
             // Attempt to use the generic view if no specific data loading logic was hit but param is known
            if (in_array($parametre, ['specialite', 'categorie_menu'])) { // Example: if these are simple text fields
                 $viewName = 'data_views/parametre-general';
            } else {
                 return $this->error("Le paramètre '$parametre' n'est pas géré ou n'a pas de vue associée.");
            }
        }


        return Response::view(
            view: $viewName,
            data: $viewData, // Pass all collected data to the view
            json: [ // JSON response for AJAX call, can include HTML for partial update
                'statut' => 'succes',
                'message' => 'Formulaire chargé.',
                // 'htmlFormContent' => $this->renderPartial($viewName, $viewData) // Optional: render partial HTML
            ]
        );
    }

    // Placeholder for CRUD operations on these parameters
    // Each operation would typically be a separate method (ajouterParametre, modifierParametre, supprimerParametre)
    // and would use the corresponding DAO.

    public function executerActionParametre(): Response
    {
        // $action = $this->request->getPostParams('action_parametre'); // e.g., 'ajouter', 'modifier', 'supprimer'
        // $parametreType = $this->request->getPostParams('parametre_type');
        // $donnees = $this->request->getPostParams('donnees'); // Form data

        // $dao = null;
        // switch ($parametreType) {
        //     case 'ue': $dao = $this->ueDAO; break;
        //     case 'ecue': $dao = $this->ecueDAO; break;
        //     // ... add all other cases
        //     default: return $this->error("Type de paramètre non géré pour l'action.");
        // }

        // switch ($action) {
        //     case 'ajouter':
        //         // if ($dao->creer($donnees)) return $this->succes(ucfirst($parametreType) . " ajouté avec succès.");
        //         // else return $this->error("Erreur d'ajout.");
        //         break;
        //     case 'modifier':
        //         // $id = $this->request->getPostParams('id');
        //         // if ($dao->mettreAJour($id, $donnees)) return $this->succes(ucfirst($parametreType) . " modifié avec succès.");
        //         // else return $this->error("Erreur de modification.");
        //         break;
        //     case 'supprimer':
        //         // $id = $this->request->getPostParams('id');
        //         // if ($dao->supprimer($id)) return $this->succes(ucfirst($parametreType) . " supprimé avec succès.");
        //         // else return $this->error("Erreur de suppression.");
        //         break;
        //     default: return $this->error("Action non reconnue.");
        // }
        return $this->info("Actions CRUD pour les paramètres généraux à implémenter.");
    }

}