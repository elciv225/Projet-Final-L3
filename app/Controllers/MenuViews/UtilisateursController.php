<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\UtilisateurDAO;
use App\Dao\EtudiantDAO;
use App\Dao\EnseignantDAO;
use App\Dao\PersonnelAdministratifDAO;
use App\Dao\CategorieUtilisateurDAO;
use App\Dao\GroupeUtilisateurDAO; // For assigning groups
use App\Dao\NiveauEtudeDAO; // For etudiant form
use App\Dao\FonctionDAO; // For personnel form
use App\Dao\GradeDAO; // For enseignant form
use System\Http\Response;

class UtilisateursController extends Controller
{
    private UtilisateurDAO $utilisateurDAO;
    private EtudiantDAO $etudiantDAO;
    private EnseignantDAO $enseignantDAO;
    private PersonnelAdministratifDAO $personnelAdministratifDAO;
    private CategorieUtilisateurDAO $categorieUtilisateurDAO;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;
    private NiveauEtudeDAO $niveauEtudeDAO;
    private FonctionDAO $fonctionDAO;
    private GradeDAO $gradeDAO;
    private \App\Dao\TypeUtilisateurDAO $typeUtilisateurDAO; // Added
    private \App\Dao\NiveauAccesDonneesDAO $niveauAccesDonneesDAO; // Added


    public function __construct()
    {
        parent::__construct();
        $this->utilisateurDAO = new UtilisateurDAO($this->pdo);
        $this->etudiantDAO = new EtudiantDAO($this->pdo);
        $this->enseignantDAO = new EnseignantDAO($this->pdo);
        $this->personnelAdministratifDAO = new PersonnelAdministratifDAO($this->pdo);
        $this->categorieUtilisateurDAO = new CategorieUtilisateurDAO($this->pdo);
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
        $this->niveauEtudeDAO = new NiveauEtudeDAO($this->pdo);
        $this->fonctionDAO = new FonctionDAO($this->pdo);
        $this->gradeDAO = new GradeDAO($this->pdo);
        $this->typeUtilisateurDAO = new \App\Dao\TypeUtilisateurDAO($this->pdo); // Added
        $this->niveauAccesDonneesDAO = new \App\Dao\NiveauAccesDonneesDAO($this->pdo); // Added
    }

    public function index(): Response
    {
        // Fetch all users with their category details for the main list display
        // This would require a more complex query in UtilisateurDAO or multiple calls
        // $utilisateurs = $this->utilisateurDAO->recupererTousAvecDetailsCategorie();

        $data = [
            'title' => 'Gestion des Utilisateurs',
            'heading' => 'Utilisateurs',
            'content' => 'Gestion des utilisateurs de l\'établissement (étudiants, enseignants, personnel administratif).',
            // 'utilisateurs' => $utilisateurs, // Example: Load initial list of users
            'categories' => $this->categorieUtilisateurDAO->recupererTous('libelle', 'ASC'),
            'typesUtilisateur' => $this->typeUtilisateurDAO->recupererTous('libelle', 'ASC'),
            'groupesUtilisateur' => $this->groupeUtilisateurDAO->recupererTous('libelle', 'ASC'),
            'niveauxAcces' => $this->niveauAccesDonneesDAO->recupererTous('libelle', 'ASC'),
        ];
        return Response::view('menu_views/utilisateurs', $data);
    }

    public function chargerFormulaireCategorie(): Response
    {
        $categorieSlug = $this->request->getPostParams('categorie-utilisateur'); // e.g., 'etudiant', 'enseignant'
        $idUtilisateur = $this->request->getPostParams('id_utilisateur'); // For modification
        $utilisateur = null;
        $detailsUtilisateur = null; // For etudiant, enseignant, personnel_administratif specific data

        if (!$categorieSlug) {
            return $this->error('Aucune catégorie sélectionnée.');
        }

        // Assuming $categorieSlug is the actual ID like 'CAT_ETUD', 'CAT_ENS'
        $categorie = $this->categorieUtilisateurDAO->recupererParId($categorieSlug);
        if (!$categorie) {
             return $this->error("Catégorie utilisateur invalide pour l'ID: $categorieSlug");
        }

        $viewData = [
            'categorieUtilisateur' => ucfirst($categorie->getLibelle()), // Adjusted to use generic getLibelle if model has it, or direct property
            'categorieId' => $categorie->getId(), // Pass the actual ID
            'categorieSlug' => $categorieSlug, // Keep original slug for view logic if needed, though redundant if it's the ID
            'groupes' => $this->groupeUtilisateurDAO->recupererTous('libelle', 'ASC'), // Assuming libelle for groupe_utilisateur
            'utilisateur' => null,
            'detailsUtilisateur' => null,
        ];

        if ($idUtilisateur) {
            $utilisateur = $this->utilisateurDAO->recupererParId($idUtilisateur);
            $viewData['utilisateur'] = $utilisateur;
            // Fetch specific details based on category if modifying
            if ($utilisateur) {
                switch ($categorieSlug) {
                    case 'etudiant':
                        $detailsUtilisateur = $this->etudiantDAO->recupererParIdUtilisateur($idUtilisateur);
                        break;
                    case 'enseignant':
                        $detailsUtilisateur = $this->enseignantDAO->recupererParIdUtilisateur($idUtilisateur);
                        break;
                    case 'administratif': // Assuming 'administratif' is the slug for personnel_administratif
                        $detailsUtilisateur = $this->personnelAdministratifDAO->recupererParIdUtilisateur($idUtilisateur);
                        break;
                }
                $viewData['detailsUtilisateur'] = $detailsUtilisateur;
            }
        }

        $viewName = match ($categorieSlug) {
            'etudiant' => {
                $viewData['niveaux_etude'] = $this->niveauEtudeDAO->recupererTous('libelle_niveau_etude', 'ASC');
                yield 'data_views/etudiants';
            },
            'enseignant' => {
                $viewData['grades'] = $this->gradeDAO->recupererTous('libelle_grade', 'ASC');
                 // Enseignants might also have fonctions, if applicable through a join table or directly.
                $viewData['fonctions'] = $this->fonctionDAO->recupererTous('libelle_fonction', 'ASC');
                yield 'data_views/personnel-universite'; // This view needs to handle both enseignant and administratif
            },
            'administratif' => { // Assuming 'administratif' is the slug for personnel_administratif
                $viewData['fonctions'] = $this->fonctionDAO->recupererTous('libelle_fonction', 'ASC');
                yield 'data_views/personnel-universite';
            },
            default => 'errors/404', // Should not happen if $categorie is valid
        };
        $viewName = $viewName instanceof \Generator ? $viewName->current() : $viewName;


        if ($viewName === 'errors/404') {
            return $this->error("Vue non trouvée pour la catégorie '$categorieSlug'.");
        }

        // For AJAX response, often a partial HTML is sent
        // $htmlForm = $this->renderPartial($viewName, $viewData); // Hypothetical method to render only the view part

        return Response::view(
            view: $viewName,
            data: $viewData,
            json: [
                'statut' => 'succes',
                'message' => 'Formulaire chargé.',
                // 'htmlFormContent' => $htmlForm // Send pre-rendered HTML
            ]
        );
    }

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation');
        $categorieSlug = $this->request->getPostParams('categorie_slug'); // Ensure this is sent from form
        $idUtilisateur = $this->request->getPostParams('id_utilisateur'); // For modification/suppression

        if (!$operation) {
            return $this->error("Aucune action spécifiée.");
        }
        if (!$categorieSlug && ($operation == 'ajouter' || $operation == 'modifier')) {
            return $this->error("Catégorie d'utilisateur non spécifiée pour l'action.");
        }

        // Basic data common to all users
        $donneesUtilisateur = [
            'nom' => $this->request->getPostParams('nom'),
            'prenom' => $this->request->getPostParams('prenom'),
            'login' => $this->request->getPostParams('login'),
            'email' => $this->request->getPostParams('email'),
            'telephone' => $this->request->getPostParams('telephone'),
            'adresse' => $this->request->getPostParams('adresse'),
            // Assuming $categorieSlug is the actual ID from the form, e.g., 'CAT_ETUD'
            'id_categorie_utilisateur' => $categorieSlug,
            'id_groupe_utilisateur' => $this->request->getPostParams('id_groupe_utilisateur'),
        ];
        $password = $this->request->getPostParams('mot_de_passe');
        if ($password && $operation == 'ajouter') { // Only add password if provided, hash it
            $donneesUtilisateur['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }
         if ($password && $operation == 'modifier' && !empty($password)) { // If modifying and password is not empty
            $donneesUtilisateur['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }


        // Specific data based on category
        $donneesSpecifiques = [];
        switch ($categorieSlug) {
            case 'etudiant':
                $donneesSpecifiques = [
                    'num_matricule' => $this->request->getPostParams('num_matricule'),
                    'date_naissance' => $this->request->getPostParams('date_naissance'),
                    'lieu_naissance' => $this->request->getPostParams('lieu_naissance'),
                    'id_niveau_etude' => $this->request->getPostParams('id_niveau_etude'),
                ];
                break;
            case 'enseignant':
                $donneesSpecifiques = [
                    // 'id_utilisateur' will be set after user creation/retrieval
                    'id_grade' => $this->request->getPostParams('id_grade_enseignant'), // Ensure form field name consistency
                    // Potentially 'id_fonction' if applicable directly or through another table
                ];
                break;
            case 'administratif':
                $donneesSpecifiques = [
                    // 'id_utilisateur' will be set after user creation/retrieval
                    'id_fonction' => $this->request->getPostParams('id_fonction_personnel'), // Ensure form field name consistency
                ];
                break;
        }


        return match ($operation) {
            'ajouter' => $this->traiterAjout($donneesUtilisateur, $donneesSpecifiques, $categorieSlug),
            'modifier' => $this->traiterModification($idUtilisateur, $donneesUtilisateur, $donneesSpecifiques, $categorieSlug),
            'supprimer' => $this->traiterSuppression($idUtilisateur, $categorieSlug), // Pass categorieSlug to know which sub-table to clean
            default => $this->error("Action non reconnue: '$operation'"),
        };
    }

    private function traiterAjout(array $donneesUtilisateur, array $donneesSpecifiques, string $categorieSlug): Response
    {
        $this->pdo->beginTransaction();
        try {
            if (!$this->utilisateurDAO->creer($donneesUtilisateur)) {
                throw new \Exception("Erreur lors de la création de l'utilisateur de base.");
            }
            $idUtilisateur = $this->pdo->lastInsertId();
            $donneesSpecifiques['id_utilisateur'] = $idUtilisateur;

            $daoSpecifique = null;
            switch ($categorieSlug) {
                case 'etudiant': $daoSpecifique = $this->etudiantDAO; break;
                case 'enseignant': $daoSpecifique = $this->enseignantDAO; break;
                case 'administratif': $daoSpecifique = $this->personnelAdministratifDAO; break;
                default: throw new \Exception("Catégorie spécifique non gérée pour l'ajout.");
            }

            if (!$daoSpecifique->creer($donneesSpecifiques)) {
                 throw new \Exception("Erreur lors de la création des détails spécifiques de l'utilisateur ($categorieSlug).");
            }

            $this->pdo->commit();
            return $this->succes("Utilisateur ($categorieSlug) ajouté avec succès. ID: $idUtilisateur");
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return $this->error("Erreur d'ajout: " . $e->getMessage());
        }
    }

    private function traiterModification(string $idUtilisateur, array $donneesUtilisateur, array $donneesSpecifiques, string $categorieSlug): Response
    {
        if (!$idUtilisateur) return $this->error("ID Utilisateur manquant pour la modification.");

        $this->pdo->beginTransaction();
        try {
            // Remove id_categorie_utilisateur and id_groupe_utilisateur if they are not supposed to be changed here or handle separately
            // For simplicity, assume they can be updated.
            if (!$this->utilisateurDAO->mettreAJour($idUtilisateur, $donneesUtilisateur)) {
                // It might not throw an error if no rows were affected but query was valid. Check affected rows if DAO returns it.
                // For now, assume failure if not explicitly successful.
                // throw new \Exception("Erreur lors de la mise à jour de l'utilisateur de base.");
            }

            $daoSpecifique = null;
            $idSpecifique = null; // The ID in the specific table (etudiants, enseignants, etc.)

            switch ($categorieSlug) {
                case 'etudiant':
                    $daoSpecifique = $this->etudiantDAO;
                    $etudiant = $this->etudiantDAO->recupererParIdUtilisateur($idUtilisateur);
                    if ($etudiant) $idSpecifique = $etudiant->getId(); // Assuming getId() method on Etudiant model
                    break;
                case 'enseignant':
                    $daoSpecifique = $this->enseignantDAO;
                    $enseignant = $this->enseignantDAO->recupererParIdUtilisateur($idUtilisateur);
                     if ($enseignant) $idSpecifique = $enseignant->getId();
                    break;
                case 'administratif':
                    $daoSpecifique = $this->personnelAdministratifDAO;
                    $personnel = $this->personnelAdministratifDAO->recupererParIdUtilisateur($idUtilisateur);
                    if ($personnel) $idSpecifique = $personnel->getId();
                    break;
                default: throw new \Exception("Catégorie spécifique non gérée pour la modification.");
            }

            if ($idSpecifique) {
                 if (!$daoSpecifique->mettreAJour($idSpecifique, $donneesSpecifiques)) {
                    // As above, check for actual failure vs. no change.
                    // throw new \Exception("Erreur lors de la mise à jour des détails spécifiques ($categorieSlug).");
                 }
            } else if (!empty($donneesSpecifiques)) { // If no specific record exists, but data is provided, create it.
                 $donneesSpecifiques['id_utilisateur'] = $idUtilisateur;
                 if (!$daoSpecifique->creer($donneesSpecifiques)) {
                    throw new \Exception("Erreur lors de la création des détails spécifiques manquants ($categorieSlug).");
                 }
            }


            $this->pdo->commit();
            return $this->succes("Utilisateur ($categorieSlug) modifié avec succès.");
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return $this->error("Erreur de modification: " . $e->getMessage());
        }
    }

    private function traiterSuppression(string $idUtilisateur, string $categorieSlug): Response
    {
         if (!$idUtilisateur) return $this->error("ID Utilisateur manquant pour la suppression.");

        $this->pdo->beginTransaction();
        try {
            // First, delete from the specific table (etudiant, enseignant, etc.)
            $daoSpecifique = null;
            $idSpecifique = null;
             switch ($categorieSlug) {
                case 'etudiant':
                    $daoSpecifique = $this->etudiantDAO;
                    $etudiant = $this->etudiantDAO->recupererParIdUtilisateur($idUtilisateur);
                    if ($etudiant) $idSpecifique = $etudiant->getId();
                    break;
                case 'enseignant':
                    $daoSpecifique = $this->enseignantDAO;
                    $enseignant = $this->enseignantDAO->recupererParIdUtilisateur($idUtilisateur);
                     if ($enseignant) $idSpecifique = $enseignant->getId();
                    break;
                case 'administratif':
                    $daoSpecifique = $this->personnelAdministratifDAO;
                    $personnel = $this->personnelAdministratifDAO->recupererParIdUtilisateur($idUtilisateur);
                    if ($personnel) $idSpecifique = $personnel->getId();
                    break;
                // No default, as we only proceed if category is known.
            }

            if ($daoSpecifique && $idSpecifique) {
                if (!$daoSpecifique->supprimer($idSpecifique)) {
                     throw new \Exception("Erreur lors de la suppression des détails de l'utilisateur ($categorieSlug). Vérifiez les contraintes (historiques, etc.).");
                }
            } elseif ($daoSpecifique && !$idSpecifique) {
                // No specific record to delete, which is fine, proceed to delete base user.
            } elseif (!$daoSpecifique && $categorieSlug) {
                 throw new \Exception("Catégorie spécifique '$categorieSlug' non gérée pour la suppression.");
            }


            // Then, delete from the main utilisateur table
            if (!$this->utilisateurDAO->supprimer($idUtilisateur)) {
                throw new \Exception("Erreur lors de la suppression de l'utilisateur de base. Vérifiez les contraintes (rapports, discussions, etc.).");
            }

            $this->pdo->commit();
            return $this->succes("Utilisateur ($categorieSlug) et ses détails associés supprimés avec succès.");
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            // Provide a more user-friendly message for foreign key constraint errors
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 return $this->error("Impossible de supprimer cet utilisateur car il est référencé ailleurs (par exemple, dans des rapports, discussions, historiques). Veuillez d'abord supprimer ou réassigner ces références.");
            }
            return $this->error("Erreur de suppression: " . $e->getMessage());
        }
    }
}