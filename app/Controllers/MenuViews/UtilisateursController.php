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

class UtilisateursController extends Controller
{
    protected PDO $pdo;

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

        $data = [
            'typesUtilisateur' => $daoTypeUtilisateur->recupererTous(),
            'groupesUtilisateur' => $daoGroupeUtilisateur->recupererTous(),
            'utilisateurs' => $daoUtilisateur->recupererTousAvecDetails()
        ];

        return Response::view('menu_views/utilisateurs', $data, json: [
            'statut' => $statut,
            'message' => $message
        ]);
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

    private function validerChampsRequis(array $post): ?Response
    {
        $requiredFields = [
            'nom-utilisateur' => 'Nom',
            'prenom-utilisateur' => 'Prénom(s)',
            'email-utilisateur' => 'Email',
            'date-naissance' => 'Date de naissance',
            'id-type-utilisateur' => 'Type d\'utilisateur',
            'id-groupe-utilisateur' => 'Groupe utilisateur'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty(trim($post[$field] ?? ''))) {
                return $this->error("Le champ '{$label}' est obligatoire.");
            }
        }
        return null;
    }

    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();
        $validationError = $this->validerChampsRequis($post);
        if ($validationError) {
            return $validationError;
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
            return $this->error("ID de l'utilisateur manquant pour la modification.");
        }

        $validationError = $this->validerChampsRequis($post);
        if ($validationError) {
            return $validationError;
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
