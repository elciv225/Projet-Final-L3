<?php

namespace App\Controllers\MenuViews;

use App\Dao\GroupeUtilisateurDAO;
use App\Dao\TypeUtilisateurDAO;
use App\Dao\UtilisateurDAO;
use App\Models\Utilisateur;
use PDO;
use App\Controllers\Controller;
use System\Database\Database;
use System\Http\Response;

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

    public function chargerFormulaireCategorie(): Response
    {
        $categorie = $this->request->getPostParams('categorie-utilisateur');

        if (!$categorie) {
            return Response::json(['statut' => 'erreur', 'message' => 'Aucune catégorie sélectionnée.']);
        }

        $viewName = match ($categorie) {
            'etudiant' => 'data_views/etudiants',
            'enseignant', 'administratif' => 'data_views/personnel-universite',
            default => null
        };

        if ($viewName === null) {
            return Response::json(['statut' => 'erreur', 'message' => 'Catégorie invalide.']);
        }

        return Response::view(
            view: $viewName,
            data: ['categorieUtilisateur' => ucfirst($categorie)],
            json: ['statut' => 'succes']
        );
    }

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        if (!$operation) {
            return $this->error("Aucune Action");
        }
        return match ($operation) {
            'ajouter' => $this->traiterAjout(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue"),
        };
    }

    private function traiterAjout(): Response
    {
        $post = $this->request->getPostParams();

        dd($post);

        // Validation des champs obligatoires
        $requiredFields = [
            'nom-utilisateur' => 'Nom',
            'prenom-utilisateur' => 'Prénom',
            'email-utilisateur' => 'Email',
            'date-naissance' => 'Date de naissance',
            'itype-utilisateur' => 'Type d\'utilisateur',
            'id-groupe-utilisateur' => 'Groupe utilisateur'
        ];


        foreach ($requiredFields as $field => $label) {
            if (!isset($post[$field]) || $post[$field] === '' || $post[$field] === null) {
                return $this->error("Le champ '{$label}' est obligatoire.");
            }
        }

        // Validation des références existantes
        $daoTypeUtilisateur = new TypeUtilisateurDAO($this->pdo);
        $daoGroupeUtilisateur = new GroupeUtilisateurDAO($this->pdo);

        // Vérifier que le type d'utilisateur existe
        if (!$daoTypeUtilisateur->recupererParId($post['id-type-utilisateur'])) {
            return $this->error("Le type d'utilisateur sélectionné n'existe pas.");
        }

        // Vérifier que le groupe d'utilisateur existe
        if (!$daoGroupeUtilisateur->recupererParId($post['id-groupe-utilisateur'])) {
            return $this->error("Le groupe d'utilisateur sélectionné n'existe pas.");
        }

        // Validation de l'email
        if (!filter_var($post['email-utilisateur'], FILTER_VALIDATE_EMAIL)) {
            return $this->error("L'adresse email n'est pas valide.");
        }

        $dao = new UtilisateurDAO($this->pdo);

        // Vérifier si l'email existe déjà
        $emailExiste = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        $emailExiste->execute([$post['email-utilisateur']]);
        if ($emailExiste->fetchColumn() > 0) {
            return $this->error("Cette adresse email est déjà utilisée.");
        }

        // Génération du login
        $nom = preg_replace('/[^a-zA-Z]/', '', $post['nom-utilisateur']);
        $nomPart = strtoupper(substr($nom, 0, 4));

        $datePart = '';
        if (!empty($post['date-naissance'])) {
            try {
                $date = new \DateTime($post['date-naissance']);
                $datePart = $date->format('dmy');
            } catch (\Exception $e) {
                return $this->error("Format de date de naissance invalide.");
            }
        }

        if (strlen($nomPart) < 4 || empty($datePart)) {
            return $this->error("Le nom doit contenir au moins 4 lettres et la date de naissance est nécessaire pour générer le login.");
        }

        $baseLogin = $nomPart . $datePart;
        $login = $baseLogin . '0001';
        $counter = 1;

        while ($dao->recupererParId($login)) {
            $counter++;
            $sequence = str_pad($counter, 4, '0', STR_PAD_LEFT);
            $login = $baseLogin . $sequence;
        }
        $id = $login;

        // Création de l'utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->setId($id);
        $utilisateur->setLogin($login);
        $utilisateur->setNom($post['nom-utilisateur']);
        $utilisateur->setPrenoms($post['prenom-utilisateur']);
        $utilisateur->setEmail($post['email-utilisateur']);
        $utilisateur->setDateNaissance($post['date-naissance']);
        $utilisateur->setTypeUtilisateurId($post['id-type-utilisateur']);
        $utilisateur->setGroupeUtilisateurId($post['id-groupe-utilisateur']);
        $utilisateur->setMotDePasse(password_hash('password123', PASSWORD_DEFAULT));

        try {
            if ($dao->creer($utilisateur)) {
                return $this->info("Utilisateur '{$id}' créé avec succès. Le tableau sera rafraîchi.");
            }
            return $this->error("Erreur lors de la création de l'utilisateur.");
        } catch (\PDOException $e) {
            // Log l'erreur pour le debugging
            error_log("Erreur création utilisateur: " . $e->getMessage());
            return $this->error("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
        }
    }

    private function traiterModification(): Response
    {
        return $this->info("Modifier un utilisateur");
    }

    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->error("Aucun ID sélectionné pour la suppression.");
        }

        $dao = new UtilisateurDAO($this->pdo);
        $errors = 0;
        foreach ($ids as $id) {
            if (!$dao->supprimer($id)) {
                $errors++;
            }
        }

        if ($errors > 0) {
            return $this->error("$errors utilisateur(s) n'ont pas pu être supprimés car ils sont référencés ailleurs.");
        }
        return $this->info("La sélection a été supprimée avec succès.");
    }
}