<?php

namespace App\Dao;

use PDO;
use App\Models\AffectationEncadrant;

class AffectationEncadrantDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'affectation_encadrant', AffectationEncadrant::class, 'utilisateur_id');
    }

    /**
     * Affecte un encadrant à un rapport
     * @param string $enseignantId
     * @param string $rapportId
     * @param string $statutJuryId
     * @param string $dateAffectation
     * @return bool
     */
    public function affecterEncadrant(string $enseignantId, string $rapportId, string $statutJuryId, string $dateAffectation): bool
    {
        $sql = "INSERT INTO affectation_encadrant (utilisateur_id, rapport_etudiant_id, statut_jury_id, date_affectation) 
                VALUES (:utilisateur_id, :rapport_etudiant_id, :statut_jury_id, :date_affectation)";
        
        return $this->executerRequeteAction($sql, [
            'utilisateur_id' => $enseignantId,
            'rapport_etudiant_id' => $rapportId,
            'statut_jury_id' => $statutJuryId,
            'date_affectation' => $dateAffectation
        ]) > 0;
    }

    /**
     * Vérifie si un rapport a déjà des encadrants assignés
     * @param string $rapportId
     * @return bool
     */
    public function aDejaEncadrants(string $rapportId): bool
    {
        $sql = "SELECT COUNT(*) as nb FROM affectation_encadrant WHERE rapport_etudiant_id = :rapport_id";
        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);
        
        return isset($result[0]['nb']) && $result[0]['nb'] > 0;
    }

    /**
     * Récupère les encadrants d'un rapport
     * @param string $rapportId
     * @return array
     */
    public function getEncadrantsRapport(string $rapportId): array
    {
        $sql = "SELECT ae.*, u.nom, u.prenoms, u.email, sj.libelle as statut_jury_libelle
                FROM affectation_encadrant ae
                JOIN utilisateur u ON ae.utilisateur_id = u.id
                JOIN statut_jury sj ON ae.statut_jury_id = sj.id
                WHERE ae.rapport_etudiant_id = :rapport_id
                ORDER BY ae.statut_jury_id";
        
        return $this->executerSelect($sql, ['rapport_id' => $rapportId]);
    }

    /**
     * Récupère les enseignants disponibles (ceux qui ont le moins d'affectations)
     * @param int $limit
     * @return array
     */
    public function getEnseignantsDisponibles(int $limit = 10): array
    {
        $sql = "SELECT e.utilisateur_id, u.nom, u.prenoms, u.email, 
                       COUNT(ae.utilisateur_id) as nb_affectations
                FROM enseignant e
                JOIN utilisateur u ON e.utilisateur_id = u.id
                LEFT JOIN affectation_encadrant ae ON e.utilisateur_id = ae.utilisateur_id
                GROUP BY e.utilisateur_id, u.nom, u.prenoms, u.email
                ORDER BY nb_affectations ASC
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Assigne aléatoirement des encadrants à un rapport
     * @param string $rapportId
     * @return bool
     */
    public function assignerEncadrantsAleatoires(string $rapportId): bool
    {
        // Vérifier si le rapport a déjà des encadrants
        if ($this->aDejaEncadrants($rapportId)) {
            return false;
        }
        
        // Récupérer les enseignants disponibles
        $enseignants = $this->getEnseignantsDisponibles(10);
        
        if (count($enseignants) < 2) {
            return false; // Pas assez d'enseignants disponibles
        }
        
        // Mélanger le tableau pour une sélection aléatoire
        shuffle($enseignants);
        
        // Assigner un directeur de mémoire (premier enseignant)
        $directeur = $enseignants[0];
        $this->affecterEncadrant(
            $directeur['utilisateur_id'],
            $rapportId,
            'STATUT_JURY_DIRECTEUR',
            date('Y-m-d')
        );
        
        // Assigner un encadreur (deuxième enseignant)
        $encadreur = $enseignants[1];
        $this->affecterEncadrant(
            $encadreur['utilisateur_id'],
            $rapportId,
            'STATUT_JURY_ENCADREUR',
            date('Y-m-d')
        );
        
        return true;
    }
}