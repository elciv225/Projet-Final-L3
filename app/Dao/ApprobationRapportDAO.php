<?php

namespace App\Dao;

use PDO;
use App\Models\ApprobationRapport;

class ApprobationRapportDAO extends DAO
{
    private PersonnelAdministratifDAO $personnelAdministratifDAO;
    private RapportEtudiantDAO $rapportEtudiantDAO;

    // CPK: utilisateur_id, rapport_etudiant_id
    // Constructeur ApprobationRapport: __construct(PersonnelAdministratif $personnelAdministratif, RapportEtudiant $rapportEtudiant, ?string $dateApprobation)

    public function __construct(
        PDO $pdo,
        PersonnelAdministratifDAO $personnelAdministratifDAO,
        RapportEtudiantDAO $rapportEtudiantDAO
    ) {
        parent::__construct($pdo, 'approbation_rapport', ApprobationRapport::class, ''); // Pas de clé primaire simple
        $this->personnelAdministratifDAO = $personnelAdministratifDAO;
        $this->rapportEtudiantDAO = $rapportEtudiantDAO;
    }

    private function hydraterApprobationRapport(array $data): ?ApprobationRapport
    {
        // $data['utilisateur_id'] est l'ID de personnel_administratif (qui est un utilisateur_id)
        $personnel = $this->personnelAdministratifDAO->recupererParId($data['utilisateur_id']);
        $rapport = $this->rapportEtudiantDAO->recupererParId($data['rapport_etudiant_id']);

        if ($personnel && $rapport) {
            return new ApprobationRapport(
                $personnel,
                $rapport,
                $data['date_approbation'] ?? null
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $utilisateurId, string $rapportEtudiantId): ?ApprobationRapport
    {
        $query = "SELECT * FROM $this->table
                  WHERE utilisateur_id = :utilisateur_id
                    AND rapport_etudiant_id = :rapport_etudiant_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':rapport_etudiant_id', $rapportEtudiantId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterApprobationRapport($data) : null;
    }

    public function creer(ApprobationRapport $approbation): bool
    {
        $data = [
            'utilisateur_id' => $approbation->getPersonnelAdministratif()->getUtilisateurId(),
            'rapport_etudiant_id' => $approbation->getRapportEtudiant()->getId(),
            'date_approbation' => $approbation->getDateApprobation()
        ];
        return parent::creer($data);
    }

    public function mettreAJour(ApprobationRapport $approbation): bool
    {
        // Seule date_approbation peut être mise à jour. La CPK ne change pas.
        $sql = "UPDATE $this->table
                SET date_approbation = :date_approbation
                WHERE utilisateur_id = :utilisateur_id
                  AND rapport_etudiant_id = :rapport_etudiant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date_approbation', $approbation->getDateApprobation());
        $stmt->bindValue(':utilisateur_id', $approbation->getPersonnelAdministratif()->getUtilisateurId());
        $stmt->bindValue(':rapport_etudiant_id', $approbation->getRapportEtudiant()->getId());
        return $stmt->execute();
    }

    public function supprimerParIdsComposites(string $utilisateurId, string $rapportEtudiantId): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE utilisateur_id = :utilisateur_id
                  AND rapport_etudiant_id = :rapport_etudiant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':rapport_etudiant_id', $rapportEtudiantId);
        return $stmt->execute();
    }

    public function recupererTousAvecObjets(?string $orderBy = null, ?string $orderType = null): array
    {
        $query = "SELECT * FROM $this->table";
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) return [];

        $personnelIds = array_unique(array_column($resultsData, 'utilisateur_id'));
        $rapportIds = array_unique(array_column($resultsData, 'rapport_etudiant_id'));

        $personnels = !empty($personnelIds) ? $this->personnelAdministratifDAO->rechercherAvecObjets(['utilisateur_id' => $personnelIds]) : [];
        $rapports = !empty($rapportIds) ? $this->rapportEtudiantDAO->rechercher(['id' => $rapportIds]) : [];

        $personnelsMap = []; foreach ($personnels as $p) { $personnelsMap[$p->getUtilisateurId()] = $p; }
        $rapportsMap = []; foreach ($rapports as $r) { $rapportsMap[$r->getId()] = $r; }

        $approbations = [];
        foreach ($resultsData as $data) {
            $personnel = $personnelsMap[$data['utilisateur_id']] ?? null;
            $rapport = $rapportsMap[$data['rapport_etudiant_id']] ?? null;

            if ($personnel && $rapport) {
                $approbations[] = new ApprobationRapport(
                    $personnel,
                    $rapport,
                    $data['date_approbation'] ?? null
                );
            }
        }
        return $approbations;
    }
}