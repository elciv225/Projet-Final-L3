<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\ActionDAO;
use App\Dao\AnneeAcademiqueDAO;
use App\Dao\CategorieMenuDAO;
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
use App\Dao\SpecialiteDAO;
use App\Dao\StatutJuryDAO;
use App\Dao\TraitementDAO;
use App\Dao\UeDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;
use App\Traits\ValidationTrait;

class ParametreGenerauxController extends Controller
{
    use ValidationTrait;
    // private PDO $pdo; // Déjà dans le parent

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    public function index(): Response
    {
        // Récupérer les statistiques pour l'affichage initial (optionnel)
        // Par exemple, le nombre d'UE, d'années académiques, etc.
        // Cette partie peut être étendue pour afficher des informations utiles sur la page principale des paramètres.
        $stats = [
            'nb_ue' => (new UeDAO($this->pdo))->compterTous(),
            'nb_annee_academique' => (new AnneeAcademiqueDAO($this->pdo))->compterTous(),
            // ... autres statistiques
        ];

        $data = [
            'title' => 'Gestion des Paramètres Généraux',
            'heading' => 'Paramètres Généraux',
            'stats' => $stats // Passer les statistiques à la vue
        ];
        return Response::view('menu_views/parametres-generaux', $data);
    }

    public function chargerVueParametre(): Response
    {
        $parametreSlug = $this->request->getGetParams('parametre') ?? // Changé en GET pour le chargement initial du formulaire
                         $this->request->getPostParams('parametre-specifique'); // Garder POST pour le select

        if (!$parametreSlug) {
            // Peut-être retourner une vue par défaut ou un message dans la zone dynamique
            return Response::html('<p class="text-center p-4">Veuillez sélectionner un paramètre à gérer.</p>');
        }

        $dao = null;
        $viewData = ['parametre_type' => $parametreSlug]; // Pour savoir quel type on traite dans la vue partielle

        // Mapper le slug du paramètre à son DAO et à son libellé pour la vue
        switch ($parametreSlug) {
            case 'annee_academique':
                $dao = new AnneeAcademiqueDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Années Académiques';
                break;
            case 'niveau_etude':
                $dao = new NiveauEtudeDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Niveaux d\'Étude';
                break;
            case 'grade':
                $dao = new GradeDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Grades';
                break;
            case 'fonction':
                $dao = new FonctionDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Fonctions';
                break;
            case 'specialite':
                $dao = new SpecialiteDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Spécialités';
                break;
            case 'entreprise':
                $dao = new EntrepriseDAO($this->pdo);
                 $viewData['parametre_libelle'] = 'Entreprises';
                // Pour entreprise, le formulaire pourrait être différent (plus de champs)
                // On pourrait charger une vue spécifique pour entreprise si besoin.
                // return Response::view('data_views/entreprise', $viewData);
                break;
            case 'categorie_menu':
                $dao = new CategorieMenuDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Catégories de Menu';
                break;
            case 'menu': // Menu lui-même (plus complexe, peut nécessiter sa propre section)
                $dao = new MenuDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Menus';
                // Pour Menu, il faut aussi les catégories de menu pour le formulaire
                $viewData['categoriesMenu'] = (new CategorieMenuDAO($this->pdo))->recupererTous();
                // Charger une vue spécifique pour les menus si le formulaire est différent
                 return Response::view('data_views/menu-param', $viewData); // Vue spécifique pour menus
            case 'ue':
                $dao = new UeDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Unités d\'Enseignement (UE)';
                // Pour UE, il faut les Niveaux d'étude pour le formulaire
                $viewData['niveauxEtude'] = (new NiveauEtudeDAO($this->pdo))->recupererTous();
                return Response::view('data_views/ue-param', $viewData); // Vue spécifique pour UE
            case 'ecue':
                $dao = new EcueDAO($this->pdo);
                $viewData['parametre_libelle'] = 'Éléments Constitutifs d\'UE (ECUE)';
                // Pour ECUE, il faut les UE pour le formulaire
                $viewData['ues'] = (new UeDAO($this->pdo))->recupererTous();
                 return Response::view('data_views/ecue-param', $viewData); // Vue spécifique pour ECUE
            // Ajouter d'autres cas pour chaque paramètre gérable
            // ...
            default:
                return Response::html('<p class="text-center p-4">Type de paramètre non reconnu.</p>');
        }

        if ($dao) {
            $viewData['elements'] = $dao->recupererTous(); // Récupérer les éléments existants
        } else {
            $viewData['elements'] = [];
        }

        return Response::view('data_views/parametre-general-form', $viewData);
    }


    public function traiterParametreGeneral(): Response
    {
        $operation = $this->request->getPostParams('operation');
        $parametreType = $this->request->getPostParams('parametre_type');
        $postData = $this->request->getPostParams(); // Toutes les données du formulaire

        if (!$operation || !$parametreType) {
            return $this->error("Opération ou type de paramètre manquant.");
        }

        $dao = $this->getDAOForParametre($parametreType);
        if (!$dao) {
            return $this->error("Type de paramètre non géré.");
        }

        // Définition des règles de validation de base
        $rules = ['libelle' => 'required|min:2']; // Champ commun

        // Ajout de règles spécifiques en fonction du type de paramètre
        switch ($parametreType) {
            case 'menu':
                $rules['vue'] = 'required';
                $rules['categorie_menu_id'] = 'required';
                break;
            case 'ue':
                $rules['credit'] = 'required|numeric|min:0';
                $rules['niveau_etude_id'] = 'required';
                break;
            case 'ecue':
                $rules['credit_ecue'] = 'required|numeric|min:0';
                $rules['ue_id'] = 'required';
                // Les heures peuvent être optionnelles ou requises avec min:0
                $rules['heure_cm'] = 'numeric|min:0';
                $rules['heure_td'] = 'numeric|min:0';
                $rules['heure_tp'] = 'numeric|min:0';
                break;
            // Pas besoin de règles supplémentaires pour les paramètres simples (libellé seul)
        }

        if ($operation === 'modifier' || $operation === 'supprimer') {
            $rules['id'] = 'required'; // L'ID est requis pour la modification et la suppression simple
        }
        if ($operation === 'supprimer' && empty($postData['ids'])) {
             // Pour suppression groupée, 'ids' est requis, sinon 'id' pour suppression simple (géré par $rules['id'])
        }


        if ($operation !== 'supprimer' || ($operation === 'supprimer' && !empty($postData['id']))) { // Valider sauf pour suppression groupée (ids)
            if (!$this->validate($postData, $rules)) {
                // En cas d'erreur de validation, il faut recharger la bonne vue partielle avec les erreurs
                // C'est plus complexe car la vue partielle dépend de $parametreType
                // Pour l'instant, on renvoie un message d'erreur générique.
                // L'idéal serait que reponseVueAvecMessage puisse aussi gérer le rechargement de la vue partielle avec erreurs.
                return $this->error($this->getAllErrorsAsString());
            }
        }


        try {
            $dataForDao = $postData; // Utiliser toutes les données postées pour le DAO, après validation
            unset($dataForDao['parametre_type'], $dataForDao['operation']); // Retirer les champs non-DAO

            switch ($operation) {
                case 'ajouter':
                    // L'ID pour les paramètres simples est souvent auto-généré ou basé sur le libellé normalisé.
                    // Si l'ID est fourni dans $postData (ex: pour 'annee_academique'), il sera utilisé.
                    // Sinon, le DAO (ou la DB) doit gérer la génération.
                    // Pour les paramètres comme 'annee_academique', l'ID est le libellé lui-même.
                    if (in_array($parametreType, ['annee_academique', 'grade', 'fonction', 'specialite', 'categorie_menu', 'categorie_utilisateur', 'niveau_acces_donnees', 'statut_jury', 'niveau_approbation', 'traitement', 'action'])) {
                        // Pour ces types, l'ID est souvent le libellé slugifié ou une valeur métier.
                        // Si la table a un ID auto-incrémenté, ne pas le setter ici.
                        // Si l'ID est le libellé, le DAO::creer doit le gérer ou il doit être passé.
                        // Pour 'annee_academique', l'ID est l'année elle-même.
                        if ($parametreType === 'annee_academique') {
                             $dataForDao['id'] = $postData['libelle']; //  Ex: '2023-2024'
                        }
                        // Pour les autres, si l'ID n'est pas auto-incrémenté, il faut le générer ou le fournir.
                        // Supposons pour l'instant que l'ID est auto-généré pour les autres cas simples si non fourni.
                    }


                    if ($dao->creer($dataForDao)) {
                        $message = ucfirst($this->slugToReadable($parametreType)) . " ajouté(e) avec succès.";
                    } else {
                        return $this->error("Échec de l'ajout. L'élément existe peut-être déjà ou les données sont incorrectes.");
                    }
                    break;

                case 'modifier':
                    if (empty($postData['id'])) return $this->error("ID manquant pour la modification.");
                    // $dataForDao contient déjà l'id et les autres champs.
                    if ($dao->mettreAJour($dataForDao)) {
                        $message = ucfirst($this->slugToReadable($parametreType)) . " mis(e) à jour avec succès.";
                    } else {
                        // Soit l'élément n'existe pas, soit aucune ligne n'a été affectée (pas de changement)
                        return $this->info("Aucune modification apportée ou élément non trouvé.");
                    }
                    break;

                case 'supprimer':
                    $idsASupprimer = $this->request->getPostParams('ids'); // Tableau d'IDs
                    if (empty($idsASupprimer)) return $this->error("Aucun ID fourni pour la suppression.");

                    $deletedCount = 0;
                    foreach ($idsASupprimer as $singleId) {
                        if ($dao->supprimer($singleId)) {
                            $deletedCount++;
                        }
                    }
                    if ($deletedCount > 0) {
                         $message = "$deletedCount " . $this->slugToReadable($parametreType) . ($deletedCount > 1 ? "s" : "") . " supprimé(e)s avec succès.";
                    } else {
                        return $this->error("Échec de la suppression ou élément(s) non trouvé(s).");
                    }
                    break;
                default:
                    return $this->error("Opération non reconnue.");
            }

            // Recharger la vue partielle du formulaire et de la table pour ce paramètre
            $viewData = [
                'parametre_type' => $parametreType,
                'parametre_libelle' => ucfirst($this->slugToReadable($parametreType)),
                'elements' => $dao->recupererTous(),
                'message_succes' => $message ?? null // Passer le message de succès à la vue partielle
            ];
            // Ajouter des données spécifiques si nécessaire pour la vue partielle
            if ($parametreType === 'menu') {
                $viewData['categoriesMenu'] = (new CategorieMenuDAO($this->pdo))->recupererTous();
                 return Response::view('data_views/menu-param', $viewData, json: ['statut' => 'succes', 'message' => $message ?? 'Opération réussie']);
            } elseif ($parametreType === 'ue') {
                $viewData['niveauxEtude'] = (new NiveauEtudeDAO($this->pdo))->recupererTous();
                return Response::view('data_views/ue-param', $viewData, json: ['statut' => 'succes', 'message' => $message ?? 'Opération réussie']);
            } elseif ($parametreType === 'ecue') {
                 $viewData['ues'] = (new UeDAO($this->pdo))->recupererTous();
                return Response::view('data_views/ecue-param', $viewData, json: ['statut' => 'succes', 'message' => $message ?? 'Opération réussie']);
            }


            return Response::view('data_views/parametre-general-form', $viewData, json: ['statut' => 'succes', 'message' => $message ?? 'Opération réussie']);

        } catch (\PDOException $e) {
            error_log("Erreur PDO dans traiterParametreGeneral : " . $e->getMessage());
            // Gérer les erreurs de contrainte d'intégrité, etc.
            if ($e->getCode() == '23000') { // Violation de contrainte (ex: unicité, clé étrangère)
                 return $this->error("Erreur de base de données : Impossible d'effectuer l'opération en raison de données existantes ou de références. Détail: " . $e->getMessage());
            }
            return $this->error("Erreur de base de données. " . $e->getMessage());
        } catch (\Exception $e) {
            error_log("Erreur dans traiterParametreGeneral : " . $e->getMessage());
            return $this->error("Une erreur système est survenue.");
        }
    }

    private function getDAOForParametre(string $parametreSlug)
    {
        return match ($parametreSlug) {
            'annee_academique' => new AnneeAcademiqueDAO($this->pdo),
            'niveau_etude' => new NiveauEtudeDAO($this->pdo),
            'grade' => new GradeDAO($this->pdo),
            'fonction' => new FonctionDAO($this->pdo),
            'specialite' => new SpecialiteDAO($this->pdo),
            'entreprise' => new EntrepriseDAO($this->pdo),
            'categorie_menu' => new CategorieMenuDAO($this->pdo),
            'menu' => new MenuDAO($this->pdo),
            'ue' => new UeDAO($this->pdo),
            'ecue' => new EcueDAO($this->pdo),
            'categorie_utilisateur' => new CategorieUtilisateurDAO($this->pdo),
            'groupe_utilisateur' => new GroupeUtilisateurDAO($this->pdo),
            'niveau_acces_donnees' => new NiveauAccesDonneesDAO($this->pdo),
            'statut_jury' => new StatutJuryDAO($this->pdo),
            'niveau_approbation' => new NiveauApprobationDAO($this->pdo),
            'traitement' => new TraitementDAO($this->pdo),
            'action' => new ActionDAO($this->pdo),
            default => null,
        };
    }

    private function slugToReadable(string $slug): string
    {
        return str_replace('_', ' ', $slug);
    }

}