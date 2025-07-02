<?php
// =================================================================
//            FICHIER: App/Controllers/UtilisateursController.php
// =================================================================
// INFO: Modifications majeures :
// 1. La méthode executerAction gère maintenant l'opération 'modifier'.
// 2. La méthode traiterModification() a été implémentée. Elle valide
//    les données et met à jour l'utilisateur via le DAO.
// 3. La méthode traiterAjout() a été légèrement ajustée pour une
//    meilleure robustesse.
// 4. La méthode traiterSuppression() a été améliorée.
// =================================================================

namespace App\Controllers\MenuViews;

use App\Dao\GroupeUtilisateurDAO;
use App\Dao\TypeUtilisateurDAO;
use App\Dao\UtilisateurDAO;
use App\Models\Utilisateur;
use PDO;
use App\Controllers\Controller;
use System\Database\Database;
use System\Http\Response;
use PDOException;
use App\Traits\ValidationTrait;

class UtilisateursController extends Controller
{
    use ValidationTrait;
    // protected PDO $pdo; // Déjà dans le parent

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    public function index(): Response
    {
        $daoTypeUtilisateur = new TypeUtilisateurDAO($this->pdo);
        $daoGroupeUtilisateur = new GroupeUtilisateurDAO($this->pdo);
        $daoUtilisateur = new UtilisateurDAO($this->pdo);

        $data = [
            'title' => 'Gestion des Utilisateurs',
            'heading' => 'Utilisateurs',
            'content' => 'Gestion des utilisateurs de l\'établissement.',
            'typesUtilisateur' => $daoTypeUtilisateur->recupererTous(),
            'groupesUtilisateur' => $daoGroupeUtilisateur->recupererTous(),
            'utilisateurs' => $daoUtilisateur->recupererTousAvecDetails()
        ];

        return Response::view('menu_views/utilisateurs', $data);
    }

    private function indexMessage(string $message, string $statut = "info"): Response
    {
        $daoTypeUtilisateur = new TypeUtilisateurDAO($this->pdo);
        $daoGroupeUtilisateur = new GroupeUtilisateurDAO($this->pdo);
        $daoUtilisateur = new UtilisateurDAO($this->pdo);

        // Données complètes pour la vue principale
        $viewData = [
            'title' => 'Gestion des Utilisateurs', // Titre pour la page complète
            'heading' => 'Utilisateurs', // Heading pour la page complète
            'typesUtilisateur' => $daoTypeUtilisateur->recupererTous(),
            'groupesUtilisateur' => $daoGroupeUtilisateur->recupererTous(),
            'utilisateurs' => $daoUtilisateur->recupererTousAvecDetailsEtIdsComplets() // Assurez-vous que cette méthode existe et renvoie les IDs
        ];

        // Données pour la vue partielle AJAX (liste des utilisateurs et potentiellement les selects si dynamiques)
        $partialViewData = [
            'utilisateurs' => $viewData['utilisateurs'],
            // Si les selects typesUtilisateur et groupesUtilisateur peuvent changer, les inclure aussi.
            // Pour l'instant, on suppose qu'ils sont stables pour le rendu partiel du tableau.
        ];

        // Ajouter les données nécessaires pour que les data-attributes de la vue partielle soient complets
        // Ceci est crucial si `recupererTousAvecDetailsEtIdsComplets` ne renvoie pas déjà tout.
        // Il faut s'assurer que $viewData['utilisateurs'] contient 'date_naissance', 'type_utilisateur_id', 'groupe_utilisateur_id'
        // Si ce n'est pas le cas, il faut modifier la requête dans UtilisateurDAO.

        return $this->reponseVueAvecMessage(
            'menu_views/utilisateurs', // Vue complète
            $viewData,
            $message,
            $statut,
            'partials/table-utilisateurs-rows', // Vue partielle pour AJAX
            $partialViewData
        );
    }

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        if (!$operation) {
            return $this->error("Aucune Action spécifiée.");
        }
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue."),
        };
    }

    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();

        $rules = [
            'nom-utilisateur' => 'required|min:2',
            'prenom-utilisateur' => 'required|min:2',
            'email-utilisateur' => 'required|email',
            'date-naissance' => 'required|date',
            'id-type-utilisateur' => 'required',
            'id-groupe-utilisateur' => 'required'
        ];

        if (!$this->validate($post, $rules)) {
            return $this->indexMessage($this->getAllErrorsAsString(), "error");
        }

        $dao = new UtilisateurDAO($this->pdo);

        $nom = preg_replace('/[^a-zA-Z]/', '', $post['nom-utilisateur']);
        $nomPart = strtoupper(substr($nom, 0, 4));

        try {
            $date = new \DateTime($post['date-naissance']);
            $datePart = $date->format('dmy');
        } catch (\Exception $_) {
            return $this->error("Format de date de naissance invalide.");
        }

        $baseLogin = $nomPart . $datePart;
        $login = $baseLogin;
        $counter = 0;

        // Boucle pour trouver un ID unique
        do {
            $suffix = str_pad($counter, 4, '0', STR_PAD_LEFT);
            $testId = $baseLogin . $suffix;
            $counter++;
        } while ($dao->recupererParId($testId));

        $id = $testId;

        $utilisateur = new Utilisateur();
        $utilisateur->setId($id);
        $utilisateur->setLogin($id); // Login est l'ID
        $utilisateur->setNom($post['nom-utilisateur']);
        $utilisateur->setPrenoms($post['prenom-utilisateur']);
        $utilisateur->setEmail($post['email-utilisateur']);
        $utilisateur->setDateNaissance($post['date-naissance']);
        $utilisateur->setTypeUtilisateurId($post['id-type-utilisateur']);
        $utilisateur->setGroupeUtilisateurId($post['id-groupe-utilisateur']);
        // Le mot de passe par défaut ne doit pas être null
        $utilisateur->setMotDePasse(password_hash('password123', PASSWORD_DEFAULT));

        try {
            // Le DAO::creer prend un objet, pas un tableau
            if ($dao->creer($utilisateur)) {
                return $this->indexMessage("Utilisateur '{$id}' créé avec succès.", "succes");
            }
            return $this->error("Une erreur inattendue est survenue lors de la création.");
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return $this->error("Cette adresse email est déjà utilisée.");
            }
            return $this->error("Erreur de base de données : " . $e->getMessage());
        }
    }

    private function traiterModification(): Response
    {
        $post = $this->request->getPostParams();
        $id = $post['id-utilisateur'] ?? null;
        if (!$id) {
            return $this->indexMessage("ID de l'utilisateur manquant pour la modification.", "error");
        }

        $rules = [
            'id-utilisateur' => 'required', // L'ID est dans $post['id-utilisateur']
            'nom-utilisateur' => 'required|min:2',
            'prenom-utilisateur' => 'required|min:2',
            'email-utilisateur' => 'required|email',
            'date-naissance' => 'required|date',
            'id-type-utilisateur' => 'required',
            'id-groupe-utilisateur' => 'required'
        ];

        if (!$this->validate($post, $rules)) {
             return $this->indexMessage($this->getAllErrorsAsString(), "error");
        }

        $dao = new UtilisateurDAO($this->pdo);
        $utilisateur = $dao->recupererParId($id);

        if (!$utilisateur) {
            return $this->error("Utilisateur non trouvé.");
        }

        // Mettre à jour les propriétés de l'objet existant
        $utilisateur->setNom($post['nom-utilisateur']);
        $utilisateur->setPrenoms($post['prenom-utilisateur']);
        $utilisateur->setEmail($post['email-utilisateur']);
        $utilisateur->setDateNaissance($post['date-naissance']);
        $utilisateur->setTypeUtilisateurId($post['id-type-utilisateur']);
        $utilisateur->setGroupeUtilisateurId($post['id-groupe-utilisateur']);
        // Note: Le login (ID) et le mot de passe ne sont pas modifiés ici.

        try {
            if ($dao->mettreAJour($utilisateur)) {
                return $this->indexMessage("Utilisateur '{$id}' mis à jour avec succès.", "succes");
            }
            return $this->info("Aucune modification détectée pour l'utilisateur '{$id}'.");
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return $this->error("Cette adresse email est déjà utilisée par un autre utilisateur.");
            }
            return $this->error("Erreur de base de données lors de la mise à jour.");
        }
    }

    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->error("Aucun ID sélectionné pour la suppression.");
        }

        $dao = new UtilisateurDAO($this->pdo);
        $succesCount = 0;
        $errorCount = 0;

        foreach ($ids as $id) {
            if ($dao->supprimer($id)) {
                $succesCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount > 0) {
            $message = "$succesCount utilisateur(s) supprimé(s). $errorCount n'a/n'ont pas pu être supprimé(s) (dépendances existantes).";
            return $this->indexMessage($message, "warning");
        }

        return $this->indexMessage("La sélection a été supprimée avec succès.", "succes");
    }
}
