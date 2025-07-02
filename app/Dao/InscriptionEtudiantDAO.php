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
        // This assumes 'annee_academique_id' can be sorted descendingly to get the latest.
        // Or, if there's a specific 'statut' or 'est_actuel' flag, that should be used.
        $inscriptions = $this->recupererParUtilisateurId($utilisateurId, 'annee_academique_id', 'DESC');
        return !empty($inscriptions) ? $inscriptions[0] : null;
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
