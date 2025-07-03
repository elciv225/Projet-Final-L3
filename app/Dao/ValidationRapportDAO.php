<?php

namespace App\Dao;

use PDO;
use App\Models\ValidationRapport;

class ValidationRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'validation_rapport', ValidationRapport::class, 'utilisateur_id');
    }

    /**
     * Valide un rapport par un enseignant
     * @param string $enseignantId
     * @param string $rapportId
     * @param string $commentaire
     * @param string $dateValidation
     * @return bool
     */
    public function validerRapport(string $enseignantId, string $rapportId, string $commentaire, string $dateValidation): bool
    {
        $sql = "INSERT INTO validation_rapport (utilisateur_id, rapport_etudiant_id, date_validation, commentaire) 
                VALUES (:utilisateur_id, :rapport_etudiant_id, :date_validation, :commentaire)";
        
        return $this->executerRequeteAction($sql, [
            'utilisateur_id' => $enseignantId,
            'rapport_etudiant_id' => $rapportId,
            'date_validation' => $dateValidation,
            'commentaire' => $commentaire
        ]) > 0;
    }

    /**
     * Vérifie si un rapport a déjà été validé
     * @param string $rapportId
     * @return bool
     */
    public function estDejaValide(string $rapportId): bool
    {
        $sql = "SELECT COUNT(*) as nb FROM validation_rapport WHERE rapport_etudiant_id = :rapport_id";
        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);
        
        return isset($result[0]['nb']) && $result[0]['nb'] > 0;
    }

    /**
     * Récupère les informations de validation pour un rapport
     * @param string $rapportId
     * @return array|null
     */
    public function getInfosValidation(string $rapportId): ?array
    {
        $sql = "SELECT vr.*, u.nom, u.prenoms, u.email 
                FROM validation_rapport vr
                JOIN utilisateur u ON vr.utilisateur_id = u.id
                WHERE vr.rapport_etudiant_id = :rapport_id";
        
        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Récupère tous les rapports validés avec les informations des enseignants et des rapports
     * @return array
     */
    public function getAllValidationsWithInfo(): array
    {
        $sql = "SELECT vr.*, u.nom, u.prenoms, u.email, re.titre, re.date_rapport, re.lien_rapport
                FROM validation_rapport vr
                JOIN utilisateur u ON vr.utilisateur_id = u.id
                JOIN rapport_etudiant re ON vr.rapport_etudiant_id = re.id
                ORDER BY vr.date_validation DESC";
        
        return $this->executerSelect($sql);
    }
}