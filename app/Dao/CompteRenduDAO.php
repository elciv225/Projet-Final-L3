<?php

namespace App\Dao;

use App\Models\CompteRendu; // Assurez-vous que le modèle CompteRendu existe
use PDO;

class CompteRenduDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        // Le nom de la table est 'compte_rendu'
        // La clé primaire est 'id'
        parent::__construct($pdo, 'compte_rendu', CompteRendu::class, 'id');
    }

    /**
     * Récupère les comptes rendus liés à un rapport spécifique.
     * @param string $rapportId
     * @return array
     */
    public function recupererParRapport(string $rapportId): array
    {
        // Cette méthode suppose une colonne `rapport_etudiant_id` dans la table `compte_rendu`
        // qui lie le compte rendu au rapport de l'étudiant.
        $sql = "SELECT * FROM compte_rendu WHERE rapport_etudiant_id = :rapport_id ORDER BY date_creation DESC"; // ou autre colonne de date
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':rapport_id' => $rapportId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->nomClasseModele);
    }
}
