<?php

namespace App\Controllers\MenuViews;

use App\Controllers\Controller;
use System\Http\Response;
use System\Database\Database;
use PDO;

class ParametreGenerauxController extends Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Database::getConnection();
    }

    public function index(): Response
    {
        // Récupérer les statistiques pour chaque type de paramètre
        $stats = $this->getParametresStats();

        $data = [
            'title' => 'Gestion des Paramètres Generaux',
            'heading' => 'Paramètres Généraux',
            'content' => 'Gestion des paramètres généraux de l\'établissement.',
            'stats' => $stats
        ];

        // Toujours retourner la vue de menu_views, jamais la page complète
        return Response::view('menu_views/parametres-generaux', $data);
    }

    public function chargerFormulaireParametreGeneraux(): Response
    {
        $parametre = $this->request->getPostParams('parametre-specifique');
        $action = $this->request->getPostParams('action') ?? 'lister';
        $nouveauParametre = $this->request->getPostParams('nouveau-parametre');
        $parametreId = $this->request->getPostParams('parametre-id');

        if (!$parametre) {
            return Response::view('menu_views/utilisateurs',
                json: [
                    'statut' => 'succes',
                    'message' => 'Aucune catégorie selectionnée.'
                ]);
        }

        // Traiter l'ajout d'un nouveau paramètre
        if ($action === 'ajouter' && !empty($nouveauParametre)) {
            $result = $this->ajouterParametre($parametre, $nouveauParametre);

            // Enregistrer l'audit
            \App\Models\Audit::enregistrer(
                "Ajout d'un nouveau paramètre '$nouveauParametre' dans la table '$parametre'",
                'AJOUT_PARAMETRE',
                null
            );

            if (!$result) {
                return Response::json([
                    'statut' => 'error',
                    'message' => 'Erreur lors de l\'ajout du paramètre.'
                ]);
            }
        }

        // Traiter la suppression d'un paramètre
        if ($action === 'supprimer' && !empty($parametreId)) {
            $result = $this->supprimerParametre($parametre, $parametreId);

            // Enregistrer l'audit
            \App\Models\Audit::enregistrer(
                "Suppression du paramètre ID '$parametreId' dans la table '$parametre'",
                'SUPPRESSION_PARAMETRE',
                null
            );

            if (!$result) {
                return Response::json([
                    'statut' => 'error',
                    'message' => 'Erreur lors de la suppression du paramètre.'
                ]);
            }
        }

        // Récupérer les données du paramètre sélectionné
        $parametreData = $this->getParametreData($parametre);

        $viewName = match ($parametre) {
            'ue',
            'ecue' => 'data_views/ue',
            'annee_academique' => 'data_views/annee_academique',
            'specialite',
            'menu',
            'categorie_menu',
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
            'action' => 'data_views/parametre-general',

            // Un cas par défaut est prévu pour gérer les valeurs inattendues ou vides.
            default => 'errors/404'
        };

        return Response::view(
            view: $viewName,
            data: [
                'parametre' => ucfirst($parametre),
                'donnees' => $parametreData,
                'action' => $action
            ],
            json: [
                'statut' => 'succes',
                'message' => $action === 'ajouter' ? 'Paramètre ajouté avec succès.' : 
                             ($action === 'supprimer' ? 'Paramètre supprimé avec succès.' : 'Paramètres chargés avec succès.')
            ]
        );
    }

    /**
     * Ajoute un nouveau paramètre dans la table spécifiée
     * @param string $table Nom de la table
     * @param string $libelle Libellé du nouveau paramètre
     * @return bool Succès de l'opération
     */
    private function ajouterParametre(string $table, string $libelle): bool
    {
        try {
            $sql = "INSERT INTO $table (libelle) VALUES (:libelle)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':libelle', $libelle);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Log l'erreur
            error_log("Erreur lors de l'ajout du paramètre: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un paramètre de la table spécifiée
     * @param string $table Nom de la table
     * @param string $id ID du paramètre à supprimer
     * @return bool Succès de l'opération
     */
    private function supprimerParametre(string $table, string $id): bool
    {
        try {
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Log l'erreur
            error_log("Erreur lors de la suppression du paramètre: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les statistiques pour chaque type de paramètre
     * @return array
     */
    private function getParametresStats(): array
    {
        $stats = [];

        // Liste des tables à compter
        $tables = [
            'ue' => 'UE',
            'ecue' => 'ECUE',
            'entreprise' => 'Entreprises',
            'niveau_etude' => 'Niveaux d\'étude',
            'grade' => 'Grades',
            'specialite' => 'Spécialités',
            'fonction' => 'Fonctions',
            'groupe_utilisateur' => 'Groupes d\'utilisateurs',
            'statut_jury' => 'Statuts de jury',
            'niveau_approbation' => 'Niveaux d\'approbation',
            'traitement' => 'Traitements',
            'action' => 'Actions',
            'categorie_menu' => 'Catégories de menu',
            'menu' => 'Menus'
        ];

        foreach ($tables as $table => $label) {
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetchColumn();

            $stats[] = [
                'table' => $table,
                'label' => $label,
                'count' => $count
            ];
        }

        return $stats;
    }

    /**
     * Récupère les données d'un paramètre spécifique
     * @param string $parametre
     * @return array
     */
    private function getParametreData(string $parametre): array
    {
        $sql = "SELECT * FROM $parametre ORDER BY id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
