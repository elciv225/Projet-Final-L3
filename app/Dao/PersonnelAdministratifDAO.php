<?php

namespace App\Dao;

use PDO;
use App\Models\PersonnelAdministratif;

class PersonnelAdministratifDAO extends DAO
{
    private UtilisateurDAO $utilisateurDAO;

    public function __construct(PDO $pdo, UtilisateurDAO $utilisateurDAO)
    {
        parent::__construct($pdo, 'personnel_administratif', PersonnelAdministratif::class, 'utilisateur_id');
        $this->utilisateurDAO = $utilisateurDAO;
    }

    // Surcharger recupererParId pour hydrater correctement PersonnelAdministratif avec son Utilisateur
    public function recupererParId(string $utilisateurId): ?PersonnelAdministratif
    {
        // D'abord, vérifier si l'entrée existe dans la table personnel_administratif
        $query = "SELECT * FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $utilisateurId);
        $stmt->execute();
        $dataPersonnel = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dataPersonnel) {
            return null; // Pas d'entrée dans personnel_administratif pour cet ID
        }

        // Ensuite, récupérer l'objet Utilisateur complet
        $utilisateur = $this->utilisateurDAO->recupererParId($utilisateurId);
        if (!$utilisateur) {
            // Cela indiquerait une incohérence de données (entrée dans personnel_administratif mais pas dans utilisateur)
            error_log("Incohérence de données: PersonnelAdministratif $utilisateurId trouvé, mais Utilisateur $utilisateurId non trouvé.");
            return null;
        }

        return new PersonnelAdministratif($utilisateur);
    }

    public function creer(PersonnelAdministratif $personnel): bool
    {
        $data = ['utilisateur_id' => $personnel->getUtilisateur()->getId()];
        return parent::creer($data);
    }

    public function rechercherAvecObjets(array $criteres, ?string $orderBy = null, ?string $orderType = null): array
    {
        $query = "SELECT pa.utilisateur_id FROM $this->table pa";
        $params = [];
        $whereClauses = [];

        if (isset($criteres['utilisateur_id'])) {
             if (is_array($criteres['utilisateur_id'])) {
                if (empty($criteres['utilisateur_id'])) {
                     $whereClauses[] = "1=0";
                } else {
                    $inParams = [];
                    foreach ($criteres['utilisateur_id'] as $key => $v) {
                        $pName = "utilisateur_id_{$key}";
                        $inParams[] = ":{$pName}";
                        $params[$pName] = $v;
                    }
                    $whereClauses[] = "pa.utilisateur_id IN (" . implode(", ", $inParams) . ")";
                }
            } else {
                $whereClauses[] = "pa.utilisateur_id = :utilisateur_id_critere";
                $params['utilisateur_id_critere'] = $criteres['utilisateur_id'];
            }
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        if ($orderBy && $orderType) {
             $validOrderByColumns = ['utilisateur_id'];
            if (in_array($orderBy, $validOrderByColumns)) {
                 $query .= " ORDER BY pa.$orderBy $orderType";
            }
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $personnels = [];
        foreach ($resultsData as $data) {
            // Utiliser la méthode recupererParId surchargée pour obtenir l'objet PersonnelAdministratif complet
            $personnelInstance = $this->recupererParId($data['utilisateur_id']);
            if ($personnelInstance) {
                $personnels[] = $personnelInstance;
            }
        }
        return $personnels;
    }
}