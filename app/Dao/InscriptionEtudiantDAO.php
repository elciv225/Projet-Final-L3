<?php

namespace App\Dao;

use PDO;
use App\Models\InscriptionEtudiant;

class InscriptionEtudiantDAO extends DAO {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'inscription_etudiant', InscriptionEtudiant::class, 'id');
    }

    /**
     * Récupère toutes les inscriptions d'un étudiant donné.
     * @param string $utilisateurId L'ID de l'utilisateur (étudiant).
     * @param ?string $orderBy Colonne pour le tri.
     * @param ?string $orderType Type de tri (ASC, DESC).
     * @return array Liste d'objets InscriptionEtudiant.
     */
    public function recupererParUtilisateurId(string $utilisateurId, ?string $orderBy = 'annee_academique_id', ?string $orderType = 'DESC'): array {
        return $this->rechercher(['utilisateur_id' => $utilisateurId], $orderBy, $orderType);
    }

    /**
     * Récupère l'inscription la plus récente (ou actuelle) d'un étudiant.
     * @param string $utilisateurId L'ID de l'utilisateur (étudiant).
     * @return ?InscriptionEtudiant L'objet InscriptionEtudiant ou null si non trouvé.
     */
    public function recupererInscriptionActuelleParUtilisateurId(string $utilisateurId): ?InscriptionEtudiant {
        $sql = "
            SELECT ie.*, u.nom, u.prenoms, ne.libelle as niveau_etude_libelle, aa.id as annee_academique_id
            FROM inscription_etudiant ie
            JOIN utilisateur u ON ie.utilisateur_id = u.id
            JOIN niveau_etude ne ON ie.niveau_etude_id = ne.id
            JOIN annee_academique aa ON ie.annee_academique_id = aa.id
            WHERE ie.utilisateur_id = :utilisateur_id
            ORDER BY ie.annee_academique_id DESC
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateurId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Hydrater l'objet InscriptionEtudiant et potentiellement les objets liés
            $inscription = new InscriptionEtudiant();
            $inscription->setId($data['id']);
            $inscription->setUtilisateurId($data['utilisateur_id']);
            $inscription->setAnneeAcademiqueId($data['annee_academique_id']);
            $inscription->setNiveauEtudeId($data['niveau_etude_id']);
            $inscription->setDateInscription($data['date_inscription']);
            // $inscription->setMontant($data['montant']); // Assurez-vous que le modèle a cette propriété
            // $inscription->setTotalPaye($data['total_paye']);
            // $inscription->setResteAPayer($data['reste_a_payer']);

            // Vous pouvez aussi hydrater Utilisateur, NiveauEtude, AnneeAcademique si besoin
            return $inscription;
        }
        return null;
    }

    /**
     * Récupère une inscription spécifique avec les détails de paiement.
     */
    public function recupererInscriptionAvecPaiements(string $etudiantId, string $anneeAcademiqueId): ?array
    {
        // Cette requête doit être adaptée à votre schéma pour récupérer les infos de l'inscription
        // et la somme des paiements effectués pour cette inscription.
        $sql = "
            SELECT
                ie.id as inscription_id,
                ie.utilisateur_id,
                u.nom,
                u.prenoms,
                ie.annee_academique_id,
                ie.niveau_etude_id,
                ne.libelle as niveau_etude_libelle,
                ie.montant_initial, -- Montant total à payer pour l'inscription
                COALESCE(SUM(hp.montant_paye), 0) as total_deja_paye
            FROM inscription_etudiant ie
            JOIN utilisateur u ON ie.utilisateur_id = u.id
            JOIN niveau_etude ne ON ie.niveau_etude_id = ne.id
            LEFT JOIN historique_paiement hp ON ie.id = hp.inscription_etudiant_id
                                            AND hp.annee_academique_id = ie.annee_academique_id
                                            AND hp.utilisateur_id = ie.utilisateur_id
            WHERE ie.utilisateur_id = :etudiant_id AND ie.annee_academique_id = :annee_id
            GROUP BY ie.id, u.nom, u.prenoms, ne.libelle;
        ";
        // Note: La clause GROUP BY doit inclure toutes les colonnes non agrégées du SELECT.

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':etudiant_id' => $etudiantId, ':annee_id' => $anneeAcademiqueId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $result['reste_a_payer'] = $result['montant_initial'] - $result['total_deja_paye'];
        }
        return $result ?: null;
    }


    /**
     * Enregistre un paiement pour une inscription via une procédure stockée.
     */
    public function enregistrerPaiementViaProcedure(string $etudiantId, string $anneeAcademiqueId, float $montantPaye, string $datePaiement): bool
    {
        $sql = "CALL sp_enregistrer_paiement_inscription(:etudiant_id, :annee_id, :montant, :date_paiement, @p_success)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_STR);
        $stmt->bindParam(':annee_id', $anneeAcademiqueId, PDO::PARAM_STR);
        $stmt->bindParam(':montant', $montantPaye); // PDO::PARAM_STR ou PDO::PARAM_INT selon le type dans la DB
        $stmt->bindParam(':date_paiement', $datePaiement, PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor(); // Important pour pouvoir exécuter la requête suivante

        // Récupérer la valeur du paramètre OUT
        $result = $this->pdo->query("SELECT @p_success AS success")->fetch(PDO::FETCH_ASSOC);
        return $result && $result['success'] == 1;
    }

    /**
     * Récupère l'historique des paiements pour une inscription donnée (étudiant + année académique).
     */
    public function recupererHistoriquePaiementsPourInscription(string $etudiantId, string $anneeAcademiqueId): array
    {
        // Assurez-vous que la table historique_paiement a les colonnes utilisateur_id et annee_academique_id
        // si l'ID de l'inscription n'est pas suffisant pour joindre.
        // Si inscription_etudiant_id est unique et utilisé dans historique_paiement, la jointure est plus simple.
        $sql = "
            SELECT hp.date_paiement, hp.montant_paye, hp.reference_paiement
            FROM historique_paiement hp
            JOIN inscription_etudiant ie ON hp.inscription_etudiant_id = ie.id
                                       AND hp.utilisateur_id = ie.utilisateur_id
                                       AND hp.annee_academique_id = ie.annee_academique_id
            WHERE ie.utilisateur_id = :etudiant_id AND ie.annee_academique_id = :annee_id
            ORDER BY hp.date_paiement DESC;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':etudiant_id' => $etudiantId, ':annee_id' => $anneeAcademiqueId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Supprime toutes les inscriptions liées à un utilisateur_id.
     * Utile lors de la suppression d'un étudiant.
     * @param string $utilisateurId
     * @return bool True si la suppression a réussi ou s'il n'y avait rien à supprimer, false en cas d'erreur.
     */
    public function supprimerParUtilisateurId(string $utilisateurId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':utilisateur_id', $utilisateurId);
        return $stmt->execute();
    }

    // Vous pouvez ajouter d'autres méthodes spécifiques ici, par exemple :
    // - recupererParAnneeAcademique(string $anneeId)
    // - recupererParNiveauEtude(string $niveauId)
    // - mettreAJourStatutInscription(int $inscriptionId, string $nouveauStatut)
}
