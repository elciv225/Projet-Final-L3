<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\EnseignantDAO;
use App\Dao\PersonnelAdministratifDAO;
use App\Dao\FonctionDAO;
use App\Dao\GradeDAO;
use App\Dao\SpecialiteDAO;
use App\Dao\TypeUtilisateurDAO;
use App\Dao\GroupeUtilisateurDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;
use App\Traits\ValidationTrait;

class PersonnelsUniversiteController extends Controller
{
    use ValidationTrait;

    // protected PDO $pdo; // Déjà dans Controller parent
    private EnseignantDAO $enseignantDAO;
    private PersonnelAdministratifDAO $personnelAdministratifDAO;
    private FonctionDAO $fonctionDAO;
    private GradeDAO $gradeDAO;
    private SpecialiteDAO $specialiteDAO;
    private TypeUtilisateurDAO $typeUtilisateurDAO;
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;


    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
        $this->enseignantDAO = new EnseignantDAO($this->pdo);
        $this->personnelAdministratifDAO = new PersonnelAdministratifDAO($this->pdo);
        $this->fonctionDAO = new FonctionDAO($this->pdo);
        $this->gradeDAO = new GradeDAO($this->pdo);
        $this->specialiteDAO = new SpecialiteDAO($this->pdo);
        $this->typeUtilisateurDAO = new TypeUtilisateurDAO($this->pdo);
        $this->groupeUtilisateurDAO = new GroupeUtilisateurDAO($this->pdo);
    }

    public function index(): Response
    {
        $enseignants = $this->enseignantDAO->recupererTousAvecDetails();
        $administratifs = $this->personnelAdministratifDAO->recupererTousAvecDetails();

        // Fusionner et typer les personnels
        $personnels = [];
        foreach ($enseignants as $ens) {
            $ens['type_personnel'] = 'enseignant';
            $personnels[] = $ens;
        }
        foreach ($administratifs as $adm) {
            $adm['type_personnel'] = 'administratif';
            $personnels[] = $adm;
        }

        // Trier par nom puis prénom
        usort($personnels, function ($a, $b) {
            $cmpNom = strcmp($a['nom'], $b['nom']);
            if ($cmpNom == 0) {
                return strcmp($a['prenoms'], $b['prenoms']);
            }
            return $cmpNom;
        });

        $data = [
            'title' => 'Gestion des Personnels',
            'heading' => 'Personnels de l\'Université',
            'personnels' => $personnels,
            'fonctions' => $this->fonctionDAO->recupererTous(),
            'grades' => $this->gradeDAO->recupererTous(),
            'specialites' => $this->specialiteDAO->recupererTous(),
            'typesUtilisateur' => $this->typeUtilisateurDAO->recupererParCategories(['CAT_ENSEIGNANT', 'CAT_ADMIN']),
            'groupesUtilisateur' => $this->groupeUtilisateurDAO->recupererParCategories(['CAT_ENSEIGNANT', 'CAT_ADMIN']),
        ];
        return Response::view('menu_views/personnels-universite', $data);
    }

    private function indexMessage(string $message, string $statut = "info"): Response
    {
        $enseignants = $this->enseignantDAO->recupererTousAvecDetails();
        $administratifs = $this->personnelAdministratifDAO->recupererTousAvecDetails();
        $personnels = array_merge(
            array_map(function ($e) { $e['type_personnel'] = 'enseignant'; return $e; }, $enseignants),
            array_map(function ($a) { $a['type_personnel'] = 'administratif'; return $a; }, $administratifs)
        );
        usort($personnels, function ($a, $b) { return strcmp($a['nom'], $b['nom']) ?: strcmp($a['prenoms'], $b['prenoms']); });

        // Données complètes pour la vue principale
        $viewData = [
            'title' => 'Gestion des Personnels', // Titre pour la page complète
            'heading' => 'Personnels de l\'Université', // Heading pour la page complète
            'personnels' => $personnels,
            'fonctions' => $this->fonctionDAO->recupererTous(),
            'grades' => $this->gradeDAO->recupererTous(),
            'specialites' => $this->specialiteDAO->recupererTous(),
            'typesUtilisateur' => $this->typeUtilisateurDAO->recupererParCategories(['CAT_ENSEIGNANT', 'CAT_ADMIN']),
            'groupesUtilisateur' => $this->groupeUtilisateurDAO->recupererParCategories(['CAT_ENSEIGNANT', 'CAT_ADMIN']),
        ];

        // Données pour la vue partielle AJAX (seulement la liste des personnels)
        $partialViewData = ['personnels' => $personnels];

        return $this->reponseVueAvecMessage(
            'menu_views/personnels-universite', // Vue complète
            $viewData,
            $message,
            $statut,
            'partials/table-personnels-rows', // Vue partielle pour AJAX
            $partialViewData // Données pour la vue partielle (et JSON si pas de vue partielle AJAX)
        );
    }


    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue pour les personnels."),
        };
    }

    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();

        $rules = [
            'nom-personnel' => 'required|min:2',
            'prenom-personnel' => 'required|min:2',
            'email-personnel' => 'required|email',
            'date-naissance-personnel' => 'required|date',
            'type-personnel' => 'required|in:enseignant,administratif'
        ];

        // Règles conditionnelles pour les champs spécifiques au type de personnel
        if (($post['type-personnel'] ?? '') === 'enseignant') {
            $rules['grade-enseignant'] = 'required'; // Ou 'present' si peut être vide mais doit exister
            $rules['date-grade-enseignant'] = 'required|date';
            // Ajouter 'fonction-enseignant', 'date-fonction-enseignant', 'specialite-enseignant', 'date-specialite-enseignant'
            // si elles deviennent obligatoires pour un enseignant à la création.
            // Pour l'instant, on les laisse optionnelles comme dans la logique précédente.
        } elseif (($post['type-personnel'] ?? '') === 'administratif') {
            $rules['fonction-administratif'] = 'required';
            $rules['date-fonction-administratif'] = 'required|date';
        }

        if (!$this->validate($post, $rules)) {
            return $this->indexMessage($this->getAllErrorsAsString(), "error");
        }

        $typePersonnel = $post['type-personnel'];
        $params = [
            'nom' => $post['nom-personnel'],
            'prenoms' => $post['prenom-personnel'],
            'email' => $post['email-personnel'],
            'mot_de_passe' => 'password123', // Mot de passe par défaut
            'date_naissance' => $post['date-naissance-personnel'],
            'type_personnel_id' => $typePersonnel, // 'enseignant' ou 'administratif'
        ];

        if ($typePersonnel === 'enseignant') {
            $params['grade_id'] = $post['grade-enseignant'] ?? null;
            $params['fonction_id'] = $post['fonction-enseignant'] ?? null;
            $params['specialite_id'] = $post['specialite-enseignant'] ?? null;
            $params['date_grade'] = !empty($post['date-grade-enseignant']) ? $post['date-grade-enseignant'] : null;
            $params['date_fonction'] = !empty($post['date-fonction-enseignant']) ? $post['date-fonction-enseignant'] : null;
            $params['date_specialite'] = !empty($post['date-specialite-enseignant']) ? $post['date-specialite-enseignant'] : null;
        } elseif ($typePersonnel === 'administratif') {
            $params['fonction_id'] = $post['fonction-administratif'] ?? null;
            $params['date_fonction'] = !empty($post['date-fonction-administratif']) ? $post['date-fonction-administratif'] : null;
        }

        try {
            $dao = ($typePersonnel === 'enseignant') ? $this->enseignantDAO : $this->personnelAdministratifDAO;
            $nouveauId = $dao->ajouterViaProcedure($params);

            if (!$nouveauId) {
                throw new \Exception("L'ajout du personnel a échoué car l'ID n'a pas été retourné.");
            }
            return $this->indexMessage("Personnel '{$nouveauId}' ajouté avec succès.", "succes");
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') { // Contrainte d'unicité
                return $this->error("Erreur de duplication : L'email ou le login (matricule) existe déjà.");
            }
            // Log l'erreur réelle pour le débogage
            error_log("PDOException in traiterAjout: " . $e->getMessage() . " | SQLSTATE: " . $e->getCode());
            return $this->error("Erreur PDO lors de l'ajout : " . $e->getMessage());
        } catch (\Exception $e) {
             error_log("Exception in traiterAjout: " . $e->getMessage());
            return $this->error("Erreur système lors de l'ajout : " . $e->getMessage());
        }
    }


    private function traiterModification(): Response
    {
        $post = $this->request->getPostParams();
        $idPersonnel = $post['id-personnel'] ?? null;

        if (!$idPersonnel) return $this->indexMessage("ID du personnel manquant pour la modification.", "error");

        $rules = [
            'id-personnel' => 'required',
            'nom-personnel' => 'required|min:2',
            'prenom-personnel' => 'required|min:2',
            'email-personnel' => 'required|email',
            'date-naissance-personnel' => 'required|date',
            'type-personnel' => 'required|in:enseignant,administratif'
            // Les champs spécifiques (grade, fonction, etc.) sont optionnels à la modification générale de l'utilisateur,
            // mais leurs dates associées deviennent requises si le champ principal est fourni.
        ];

        // Validation des dates si les champs associés sont remplis
        $typePersonnel = $post['type-personnel'];
        if ($typePersonnel === 'enseignant') {
            if (!empty($post['grade-enseignant'])) $rules['date-grade-enseignant'] = 'required|date';
            if (!empty($post['fonction-enseignant'])) $rules['date-fonction-enseignant'] = 'required|date';
            if (!empty($post['specialite-enseignant'])) $rules['date-specialite-enseignant'] = 'required|date';
        } elseif ($typePersonnel === 'administratif') {
            if (!empty($post['fonction-administratif'])) $rules['date-fonction-administratif'] = 'required|date';
        }

        if (!$this->validate($post, $rules)) {
            return $this->indexMessage($this->getAllErrorsAsString(), "error");
        }

        $params = [
            'id_personnel' => $idPersonnel,
            'nom' => $post['nom-personnel'],
            'prenoms' => $post['prenom-personnel'],
            'email' => $post['email-personnel'],
            'date_naissance' => $post['date-naissance-personnel'],
            // Le type de personnel ne peut pas être modifié après création,
            // mais on le passe pour la logique de la procédure si besoin.
             'type_personnel_id' => $typePersonnel,
        ];

        if ($typePersonnel === 'enseignant') {
            $params['grade_id'] = $post['grade-enseignant'] ?? null;
            $params['fonction_id'] = $post['fonction-enseignant'] ?? null;
            $params['specialite_id'] = $post['specialite-enseignant'] ?? null;
            $params['date_grade'] = !empty($post['date-grade-enseignant']) ? $post['date-grade-enseignant'] : null;
            $params['date_fonction'] = !empty($post['date-fonction-enseignant']) ? $post['date-fonction-enseignant'] : null;
            $params['date_specialite'] = !empty($post['date-specialite-enseignant']) ? $post['date-specialite-enseignant'] : null;
        } elseif ($typePersonnel === 'administratif') {
            $params['fonction_id'] = $post['fonction-administratif'] ?? null;
            $params['date_fonction'] = !empty($post['date-fonction-administratif']) ? $post['date-fonction-administratif'] : null;
        }


        try {
            // Déterminer quel DAO utiliser en fonction du type de personnel (stocké ou déduit de l'ID)
            // Pour simplifier, on peut essayer de récupérer l'utilisateur et vérifier son type
            // ou passer le type actuel si on est sûr qu'il ne change pas.
            // Ici, on se base sur le type envoyé par le formulaire, en supposant qu'il correspond à l'existant.
            $dao = ($typePersonnel === 'enseignant') ? $this->enseignantDAO : $this->personnelAdministratifDAO;
            $success = $dao->modifierViaProcedure($params);


            if ($success) {
                return $this->indexMessage("Personnel '{$idPersonnel}' mis à jour avec succès.", "succes");
            } else {
                throw new \Exception("La modification du personnel a échoué.");
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == '45000') { // Erreur métier de la procédure
                 $message = $e->getMessage();
                if (preg_match('/1644 (.*)/', $message, $matches)) {
                    $customMessage = $matches[1];
                    return $this->error("Erreur métier : " . $customMessage);
                }
                return $this->error("Erreur métier : " . $message);
            }
             error_log("PDOException in traiterModification: " . $e->getMessage() . " | SQLSTATE: " . $e->getCode());
            return $this->error("Erreur PDO lors de la modification : " . $e->getMessage());
        } catch (\Exception $e) {
            error_log("Exception in traiterModification: " . $e->getMessage());
            return $this->error("Erreur système lors de la modification : " . $e->getMessage());
        }
    }


    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->error("Aucun ID de personnel sélectionné pour la suppression.");
        }

        $deletedCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            if (empty($id)) continue;
            try {
                // Il faut déterminer si c'est un enseignant ou un administratif.
                // On pourrait appeler une procédure générique sp_supprimer_personnel
                // ou vérifier le type avant d'appeler la bonne DAO.
                // Pour cet exemple, on suppose une procédure générique ou une logique dans le DAO.
                // Si on doit choisir le DAO :
                $user = (new \App\Dao\UtilisateurDAO($this->pdo))->recupererParId($id);
                if (!$user) {
                    $errors[] = "Personnel ID {$id} non trouvé.";
                    continue;
                }

                $dao = null;
                // Vérifier si l'utilisateur est un enseignant ou un personnel administratif
                // en essayant de le récupérer via les DAO spécifiques ou en regardant son type/groupe.
                // Cette partie dépend de comment vous distinguez les types dans la DB.
                // Supposons que le type_utilisateur_id nous donne cette info.
                $typeUtilisateur = $user->getTypeUtilisateurId();

                // Simplification: on essaie de supprimer avec les deux, un seul réussira ou aura un sens.
                // Une meilleure approche serait une procédure stockée unique `sp_supprimer_personnel`
                // qui gère la logique interne.

                $deletedEns = $this->enseignantDAO->supprimerViaProcedure($id);
                if ($deletedEns) {
                    $deletedCount++;
                } else {
                    // S'il n'a pas été supprimé en tant qu'enseignant, essayer en tant qu'admin
                    $deletedAdm = $this->personnelAdministratifDAO->supprimerViaProcedure($id);
                    if ($deletedAdm) {
                        $deletedCount++;
                    } else {
                        // Ni l'un ni l'autre, ou déjà supprimé, ou erreur non capturée par exception
                        // On peut choisir de ne pas ajouter d'erreur ici si la procédure ne renvoie pas false en cas de non-existence.
                        // Si la procédure renvoie false pour "non trouvé", alors c'est normal.
                        // Si elle renvoie false pour une "erreur non PDOException", alors c'est un problème.
                        // Pour l'instant, on assume que si rien n'est levé et false est retourné, il n'était pas de ce type ou déjà parti.
                    }
                }

            } catch (\PDOException $e) {
                 error_log("PDOException in traiterSuppression for ID {$id}: " . $e->getMessage());
                $errors[] = "Erreur BDD pour ID {$id}: " . $e->getMessage();
            } catch (\Exception $e) {
                 error_log("Exception in traiterSuppression for ID {$id}: " . $e->getMessage());
                $errors[] = "Erreur système pour ID {$id}: " . $e->getMessage();
            }
        }

        $message = "";
        if ($deletedCount > 0) {
            $message = ($deletedCount === 1) ? "1 personnel a été supprimé." : "$deletedCount personnels ont été supprimés.";
        }
        if (!empty($errors)) {
            $message .= " Erreurs rencontrées : " . implode("; ", $errors);
            return $this->indexMessage($message, "erreur");
        }
        if ($deletedCount > 0) {
             return $this->indexMessage($message, "succes");
        }
        return $this->indexMessage("Aucun personnel n'a été supprimé (peut-être déjà supprimés ou IDs invalides).", "info");
    }
}