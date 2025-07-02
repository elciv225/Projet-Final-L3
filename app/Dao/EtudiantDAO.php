<?php

namespace App\Dao;

use PDO;
use App\Models\Etudiant;

class EtudiantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'etudiant', Etudiant::class, 'utilisateur_id');
    }

    public function recupererTousAvecDetails(): array
    {
        // ... (cette méthode ne change pas)
        $sql = "
        SELECT 
            u.id, u.nom, u.prenoms, u.email, u.date_naissance, e.numero_carte,
            ie.niveau_etude_id, ne.libelle AS niveau_etude, ie.annee_academique_id, ie.montant
        FROM utilisateur u
        JOIN etudiant e ON u.id = e.utilisateur_id
        LEFT JOIN (
            SELECT i1.* FROM inscription_etudiant i1
            INNER JOIN (
                SELECT utilisateur_id, MAX(annee_academique_id) AS max_annee
                FROM inscription_etudiant GROUP BY utilisateur_id
            ) i2 ON i1.utilisateur_id = i2.utilisateur_id AND i1.annee_academique_id = i2.max_annee
        ) ie ON u.id = ie.utilisateur_id
        LEFT JOIN niveau_etude ne ON ie.niveau_etude_id = ne.id
        ORDER BY u.nom, u.prenoms;
        ";
        return $this->executerSelect($sql);
    }

    /**
     * Inscrit un nouvel étudiant en utilisant la procédure stockée sp_inscrire_etudiant.
     * La procédure gère la génération du matricule, du login et du numéro de carte.
     *
     * @param array $params Les paramètres de l'étudiant à inscrire.
     * @return string|null Le matricule du nouvel étudiant, ou null en cas d'échec.
     * @throws \PDOException En cas d'erreur de base de données.
     */
    public function inscrireViaProcedure(array $params): ?string
    {
        // CORRIGÉ: Moins de paramètres car la procédure gère plus de logique
        $sql = "CALL sp_inscrire_etudiant(?, ?, ?, ?, ?, ?, ?, ?, @p_matricule)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['nom'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['prenoms'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['mot_de_passe'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date_naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['niveau_etude_id'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['annee_academique_id'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['montant'], PDO::PARAM_INT);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $this->pdo->query("SELECT @p_matricule as matricule")->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['matricule'] : null;
    }

    /**
     * Modifie un étudiant en utilisant la procédure stockée sp_modifier_etudiant.
     *
     * @param array $params Les paramètres de l'étudiant à modifier.
     * @return bool True si la modification a réussi.
     * @throws \PDOException En cas d'erreur de base de données.
     */
    public function modifierViaProcedure(array $params): bool
    {
        $sql = "CALL sp_modifier_etudiant(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(1, $params['id-etudiant'], PDO::PARAM_STR);
        $stmt->bindParam(2, $params['nom-etudiant'], PDO::PARAM_STR);
        $stmt->bindParam(3, $params['prenom-etudiant'], PDO::PARAM_STR);
        $stmt->bindParam(4, $params['email-etudiant'], PDO::PARAM_STR);
        $stmt->bindParam(5, $params['date-naissance'], PDO::PARAM_STR);
        $stmt->bindParam(6, $params['id-niveau-etude'], PDO::PARAM_STR);
        $stmt->bindParam(7, $params['id-annee-academique'], PDO::PARAM_STR);
        $stmt->bindParam(8, $params['montant-inscription'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Supprime un ou plusieurs étudiants en appelant la procédure stockée sp_supprimer_etudiant.
     *
     * @param array|string $ids La liste des IDs ou un seul ID (matricule) des étudiants à supprimer.
     * @return int Le nombre d'étudiants traités pour suppression.
     * @throws \PDOException En cas d'erreur (la procédure propage l'erreur).
     */
    public function supprimerEtudiants(array|string $ids): int
    {
        // S'assurer que nous travaillons toujours avec un tableau pour la flexibilité
        if (is_string($ids)) {
            $ids = [$ids];
        }

        if (empty($ids)) {
            return 0;
        }

        // La transaction est gérée DANS la procédure stockée.
        try {
            // Préparer l'appel à la procédure une seule fois
            $stmt = $this->pdo->prepare("CALL sp_supprimer_etudiant(?)");
            $deletedCount = 0;

            foreach ($ids as $id) {
                if (!empty($id)) {
                    $stmt->execute([$id]);
                    // On considère que si aucune exception n'est levée, la suppression a réussi.
                    $deletedCount++;
                }
            }

            return $deletedCount;

        } catch (\PDOException $e) {
            // Propager l'exception pour qu'elle soit gérée par le contrôleur
            throw $e;
        }
    }

    /**
     * Récupère un étudiant par son ID avec les détails de son inscription pour une année académique donnée.
     * Utilisé pour la page de règlement des inscriptions.
     */
    public function recupererParIdAvecDetailsInscription(string $etudiantId, string $anneeAcademiqueId): ?array
    {
        $sql = "
            SELECT
                u.id as etudiant_id, u.nom, u.prenoms, u.email, e.numero_carte,
                ie.id as inscription_id,
                ie.niveau_etude_id, ne.libelle as niveau_etude_libelle,
                ie.annee_academique_id,
                ie.montant_initial, -- Montant total à payer pour l'inscription
                (SELECT COALESCE(SUM(hp.montant_paye), 0)
                 FROM historique_paiement hp
                 WHERE hp.utilisateur_id = ie.utilisateur_id
                   AND hp.annee_academique_id = ie.annee_academique_id
                   AND hp.inscription_etudiant_id = ie.id) as total_deja_paye
            FROM utilisateur u
            JOIN etudiant e ON u.id = e.utilisateur_id
            JOIN inscription_etudiant ie ON u.id = ie.utilisateur_id
                                       AND ie.annee_academique_id = :annee_id
            LEFT JOIN niveau_etude ne ON ie.niveau_etude_id = ne.id
            WHERE u.id = :etudiant_id;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':etudiant_id' => $etudiantId, ':annee_id' => $anneeAcademiqueId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $result['reste_a_payer'] = $result['montant_initial'] - $result['total_deja_paye'];
        }
        return $result ?: null;
    }
}
