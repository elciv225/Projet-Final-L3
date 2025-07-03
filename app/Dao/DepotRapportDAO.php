<?php

namespace App\Dao;

use PDO;
use App\Models\DepotRapport;

class DepotRapportDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'depot_rapport', DepotRapport::class, 'utilisateur_id');
    }

    /**
     * Enregistre un nouveau dépôt de rapport en utilisant une procédure stockée
     * @param string $utilisateurId
     * @param string $rapportEtudiantId
     * @param string $dateDepot
     * @return bool
     */
    public function enregistrerDepot(string $utilisateurId, string $rapportEtudiantId, string $dateDepot): bool
    {
        $sql = "CALL sp_enregistrer_depot_rapport(:utilisateur_id, :rapport_etudiant_id, :date_depot, @success, @message)";

        $this->executerRequeteAction($sql, [
            'utilisateur_id' => $utilisateurId,
            'rapport_etudiant_id' => $rapportEtudiantId,
            'date_depot' => $dateDepot
        ]);

        // Récupérer les valeurs de sortie
        $result = $this->executerSelect("SELECT @success as success, @message as message");

        if (!empty($result) && isset($result[0]['success'])) {
            // Si la procédure a retourné une erreur, on la journalise
            if ($result[0]['success'] == 0) {
                error_log("Erreur lors de l'enregistrement du dépôt: " . $result[0]['message']);
            }
            return $result[0]['success'] == 1;
        }

        return false;
    }

    /**
     * Vérifie si un étudiant a déjà déposé un rapport
     * @param string $utilisateurId
     * @return bool
     */
    public function aDejaDepose(string $utilisateurId): bool
    {
        $sql = "SELECT COUNT(*) as nb FROM depot_rapport WHERE utilisateur_id = :utilisateur_id";
        $result = $this->executerSelect($sql, ['utilisateur_id' => $utilisateurId]);

        return isset($result[0]['nb']) && $result[0]['nb'] > 0;
    }

    /**
     * Récupère les informations de dépôt pour un rapport spécifique
     * @param string $rapportId
     * @return array|null
     */
    public function getInfosDepot(string $rapportId): ?array
    {
        $sql = "SELECT dr.*, u.nom, u.prenoms, u.email 
                FROM depot_rapport dr
                JOIN utilisateur u ON dr.utilisateur_id = u.id
                WHERE dr.rapport_etudiant_id = :rapport_id";

        $result = $this->executerSelect($sql, ['rapport_id' => $rapportId]);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * Récupère tous les dépôts avec les informations des étudiants et des rapports
     * @return array
     */
    public function getAllDepotsWithInfo(): array
    {
        $sql = "SELECT dr.*, u.nom, u.prenoms, u.email, re.titre, re.date_rapport, re.lien_rapport
                FROM depot_rapport dr
                JOIN utilisateur u ON dr.utilisateur_id = u.id
                JOIN rapport_etudiant re ON dr.rapport_etudiant_id = re.id
                ORDER BY dr.date_depot DESC";

        return $this->executerSelect($sql);
    }
}
