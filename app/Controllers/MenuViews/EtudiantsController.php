<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use App\Dao\AnneeAcademiqueDAO;
use App\Dao\EtudiantDAO;
use App\Dao\NiveauEtudeDAO;
use App\Dao\UtilisateurDAO;
use App\Dao\InscriptionEtudiantDAO;
use System\Database\Database;
use System\Http\Response;
use PDO;
use PDOException;

class EtudiantsController extends Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    public function index(): Response
    {
        $etudiantDAO = new EtudiantDAO($this->pdo);
        $niveauEtudeDAO = new NiveauEtudeDAO($this->pdo);
        $anneeAcademiqueDAO = new AnneeAcademiqueDAO($this->pdo);

        $data = [
            'title' => 'Gestion des Étudiants',
            'heading' => 'Étudiants',
            'etudiants' => $etudiantDAO->recupererTousAvecDetails(),
            'niveauxEtude' => $niveauEtudeDAO->recupererTous(),
            'anneesAcademiques' => $anneeAcademiqueDAO->recupererTous(),
        ];

        return Response::view('menu_views/etudiants', $data);
    }

    private function indexMessage(string $message, string $statut = "info"): Response
    {
        $etudiantDAO = new EtudiantDAO($this->pdo);
        $data = [
            'etudiants' => $etudiantDAO->recupererTousAvecDetails()
        ];
        return Response::view('menu_views/etudiants', $data, json: [
            'statut' => $statut,
            'message' => $message
        ]);
    }

    public function executerAction(): Response
    {
        $operation = $this->request->getPostParams('operation') ?? "";
        return match ($operation) {
            'inscrire' => $this->traiterInscription(),
            'modifier' => $this->traiterModification(),
            'supprimer' => $this->traiterSuppression(),
            default => $this->error("Action non reconnue."),
        };
    }

    private function traiterInscription(): Response
    {
        $post = $this->request->getPostParams();

        $requiredFields = [
            'nom-etudiant' => 'Nom', 'prenom-etudiant' => 'Prénom(s)', 'email-etudiant' => 'Email',
            'date-naissance' => 'Date de naissance', 'id-niveau-etude' => 'Niveau d\'étude',
            'id-annee-academique' => 'Année académique', 'montant-inscription' => 'Montant'
        ];
        foreach ($requiredFields as $field => $label) {
            if (empty(trim($post[$field] ?? ''))) {
                return $this->error("Le champ '{$label}' est obligatoire.");
            }
        }

        try {
            // CORRIGÉ: Le tableau de paramètres est simplifié
            $params = [
                'nom' => $post['nom-etudiant'],
                'prenoms' => $post['prenom-etudiant'],
                'email' => $post['email-etudiant'],
                'mot_de_passe' => 'password123',
                'date_naissance' => $post['date-naissance'],
                'niveau_etude_id' => $post['id-niveau-etude'],
                'annee_academique_id' => $post['id-annee-academique'],
                'montant' => (int)$post['montant-inscription'],
            ];

            $etudiantDAO = new EtudiantDAO($this->pdo);
            $nouveauMatricule = $etudiantDAO->inscrireViaProcedure($params);

            if (!$nouveauMatricule) {
                throw new \Exception("L'inscription a échoué car le matricule n'a pas été retourné.");
            }

            // Enregistrer l'audit
            \App\Models\Audit::enregistrer(
                "Inscription d'un nouvel étudiant: {$post['nom-etudiant']} {$post['prenom-etudiant']} (Matricule: {$nouveauMatricule})",
                'INSCRIPTION_ETUDIANT',
                null
            );

            return $this->indexMessage("Étudiant '{$nouveauMatricule}' inscrit avec succès.", "succes");

        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return $this->error("Erreur de duplication : L'email ou le login (matricule) existe déjà.");
            }
            return $this->error("Erreur PDO : " . $e->getMessage());
        } catch (\Exception $e) {
            return $this->error("Erreur système : " . $e->getMessage());
        }
    } // Parfait

    /**
     * Traite la modification des informations d'un étudiant en utilisant la méthode du DAO.
     */
    private function traiterModification(): Response
    {
        // 1. Récupérer toutes les données du formulaire
        $post = $this->request->getPostParams();
        $etudiantId = $post['id-etudiant'] ?? null;

        if (!$etudiantId) {
            return $this->error("ID de l'étudiant manquant pour la modification.");
        }

        // 2. Validation des champs requis (ne change pas)
        $requiredFields = [
            'nom-etudiant' => 'Nom', 'prenom-etudiant' => 'Prénom(s)', 'email-etudiant' => 'Email',
            'date-naissance' => 'Date de naissance', 'id-niveau-etude' => 'Niveau d\'étude',
            'id-annee-academique' => 'Année académique', 'montant-inscription' => 'Montant'
        ];
        foreach ($requiredFields as $field => $label) {
            if (empty(trim($post[$field] ?? ''))) {
                return $this->error("Le champ '{$label}' est obligatoire.");
            }
        }

        try {
            // 3. Instancier le DAO et appeler la méthode de modification
            $etudiantDAO = new EtudiantDAO($this->pdo);

            // Le tableau $post contient déjà toutes les clés nécessaires
            // avec les bons noms (ex: 'nom-etudiant', 'id-etudiant', etc.)
            $success = $etudiantDAO->modifierViaProcedure($post);

            if ($success) {
                // Enregistrer l'audit
                \App\Models\Audit::enregistrer(
                    "Modification des informations de l'étudiant ID: {$etudiantId} - {$post['nom-etudiant']} {$post['prenom-etudiant']}",
                    'MODIFICATION_ETUDIANT',
                    null
                );

                return $this->indexMessage("Étudiant '{$etudiantId}' mis à jour avec succès.", "succes");
            } else {
                // Ce cas est peu probable si execute() ne lève pas d'exception, mais c'est une sécurité
                throw new \Exception("La modification a échoué sans lever d'exception.");
            }

        } catch (PDOException $e) {
            // Gérer les erreurs spécifiques de la base de données, y compris celles de la procédure.
            // Les erreurs personnalisées de la procédure sont signalées avec SQLSTATE '45000'.
            if ($e->getCode() == '45000') {
                // Extraire le message personnalisé de la procédure.
                // Le message est souvent après le code d'erreur MySQL 1644.
                $message = $e->getMessage();
                if (preg_match('/1644 (.*)/', $message, $matches)) {
                    $customMessage = $matches[1];
                    return $this->error("Erreur métier : " . $customMessage);
                }
                // Fallback si le regex ne fonctionne pas, on renvoie le message brut.
                return $this->error("Erreur métier : " . $message);
            }
            // Gérer les autres erreurs de base de données (contraintes, etc.).
            return $this->error("Erreur de base de données lors de la modification : " . $e->getMessage());
        } catch (\Exception $e) {
            return $this->error("Erreur système : " . $e->getMessage());
        }
    } // Parfait

    /**
     * Traite la suppression d'un ou plusieurs étudiants en utilisant la méthode du DAO.
     */
    private function traiterSuppression(): Response
    {
        $ids = $this->request->getPostParams('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->error("Aucun ID sélectionné pour la suppression.");
        }

        try {
            $etudiantDAO = new EtudiantDAO($this->pdo);
            $deletedCount = $etudiantDAO->supprimerEtudiants($ids);

            if ($deletedCount > 0) {
                // Enregistrer l'audit
                \App\Models\Audit::enregistrer(
                    "Suppression de $deletedCount étudiant(s) (IDs: " . implode(', ', $ids) . ")",
                    'SUPPRESSION_ETUDIANT',
                    null
                );

                $message = ($deletedCount === 1) ? "1 étudiant a été supprimé avec succès." : "$deletedCount étudiants ont été supprimés avec succès.";
                return $this->indexMessage($message, "succes");
            } else {
                return $this->error("Aucun étudiant n'a été supprimé. Ils n'existaient peut-être plus.");
            }

        } catch (\PDOException $e) {
            // Gérer les erreurs spécifiques, par exemple si un trigger bloque la suppression
            if (str_contains($e->getMessage(), 'Impossible de supprimer cet utilisateur')) {
                return $this->error("Impossible de supprimer un ou plusieurs étudiants car ils ont des données liées (rapports, évaluations, etc.).");
            }
            return $this->error("Erreur de base de données lors de la suppression.");
        } catch (\Exception $e) {
            return $this->error("Erreur système : " . $e->getMessage());
        }
    } // Parfait
}
