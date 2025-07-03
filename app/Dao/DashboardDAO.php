<?php

namespace App\Dao;

use PDO;

class DashboardDAO
{
    protected PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère les statistiques pour le tableau de bord
     * @return array
     */
    public function getStatistics(): array
    {
        $stats = [
            'totalEtudiants' => $this->getTotalEtudiants(),
            'totalEnseignants' => $this->getTotalEnseignants(),
            'totalPersonnelAdmin' => $this->getTotalPersonnelAdmin(),
            'totalCoursActifs' => $this->getTotalCoursActifs()
        ];

        return $stats;
    }

    /**
     * Récupère le nombre total d'étudiants
     * @return int
     */
    private function getTotalEtudiants(): int
    {
        $sql = "SELECT COUNT(*) as total FROM etudiant";
        $result = $this->executerSelect($sql);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Récupère le nombre total d'enseignants
     * @return int
     */
    private function getTotalEnseignants(): int
    {
        $sql = "SELECT COUNT(*) as total FROM enseignant";
        $result = $this->executerSelect($sql);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Récupère le nombre total de personnel administratif
     * @return int
     */
    private function getTotalPersonnelAdmin(): int
    {
        $sql = "SELECT COUNT(*) as total FROM personnel_administratif";
        $result = $this->executerSelect($sql);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Récupère le nombre total de cours actifs (UEs)
     * @return int
     */
    private function getTotalCoursActifs(): int
    {
        $sql = "SELECT COUNT(*) as total FROM ue";
        $result = $this->executerSelect($sql);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Récupère les activités récentes en utilisant une procédure stockée
     * @param int $limit Nombre d'activités à récupérer
     * @return array
     */
    public function getRecentActivities(int $limit = 3): array
    {
        $sql = "CALL sp_get_recent_activities(:limit)";

        return $this->executerSelect($sql, ['limit' => $limit]);
    }

    /**
     * Récupère les informations système
     * @return array
     */
    public function getSystemInfo(): array
    {
        // Ces valeurs pourraient être récupérées depuis la base de données ou calculées
        return [
            'disponibilite' => '99.9%',
            'espace_utilise' => $this->getDbSize(),
            'connexions_actives' => $this->getActiveConnections(),
            'version_systeme' => 'v1.0.0'
        ];
    }

    /**
     * Récupère la taille de la base de données
     * @return string
     */
    private function getDbSize(): string
    {
        $sql = "
            SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM 
                information_schema.tables
            WHERE 
                table_schema = DATABASE()
        ";

        $result = $this->executerSelect($sql);
        $sizeMb = $result[0]['size_mb'] ?? 0;

        if ($sizeMb < 1000) {
            return $sizeMb . ' MB';
        } else {
            return round($sizeMb / 1024, 2) . ' GB';
        }
    }

    /**
     * Récupère le nombre de connexions actives
     * @return int
     */
    private function getActiveConnections(): int
    {
        // Cette méthode pourrait être implémentée pour compter les sessions actives
        // Pour l'exemple, nous retournons une valeur fixe
        return rand(10, 100);
    }

    /**
     * Exécute une requête SELECT et retourne les résultats bruts.
     * @param string $sql La requête SQL SELECT à exécuter.
     * @param array $params Les paramètres de la requête.
     * @return array Un tableau de tableaux associatifs.
     */
    public function executerSelect(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
