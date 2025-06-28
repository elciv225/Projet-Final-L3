<?php

namespace App\Dao;

use PDO;
use App\Models\AffectationEncadrant;

class AffectationEncadrantDAO extends DAO
{
    private EnseignantDAO $enseignantDAO;
    private RapportEtudiantDAO $rapportEtudiantDAO;
    private StatutJuryDAO $statutJuryDAO;

    // CPK: utilisateur_id, rapport_etudiant_id, statut_jury_id
    // Constructeur AffectationEncadrant: __construct(Enseignant $enseignant, RapportEtudiant $rapportEtudiant, StatutJury $statutJury, ?string $dateAffectation)

    public function __construct(
        PDO $pdo,
        EnseignantDAO $enseignantDAO,
        RapportEtudiantDAO $rapportEtudiantDAO,
        StatutJuryDAO $statutJuryDAO
    ) {
        parent::__construct($pdo, 'affectation_encadrant', AffectationEncadrant::class, ''); // Pas de clé primaire simple
        $this->enseignantDAO = $enseignantDAO;
        $this->rapportEtudiantDAO = $rapportEtudiantDAO;
        $this->statutJuryDAO = $statutJuryDAO;
    }

    private function hydraterAffectation(array $data): ?AffectationEncadrant
    {
        $enseignant = $this->enseignantDAO->recupererParId($data['utilisateur_id']);
        $rapportEtudiant = $this->rapportEtudiantDAO->recupererParId($data['rapport_etudiant_id']);
        $statutJury = $this->statutJuryDAO->recupererParId($data['statut_jury_id']);

        if ($enseignant && $rapportEtudiant && $statutJury) {
            return new AffectationEncadrant(
                $enseignant,
                $rapportEtudiant,
                $statutJury,
                $data['date_affectation'] ?? null
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $utilisateurId, string $rapportEtudiantId, string $statutJuryId): ?AffectationEncadrant
    {
        $query = "SELECT * FROM $this->table
                  WHERE utilisateur_id = :utilisateur_id
                    AND rapport_etudiant_id = :rapport_etudiant_id
                    AND statut_jury_id = :statut_jury_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':rapport_etudiant_id', $rapportEtudiantId);
        $stmt->bindParam(':statut_jury_id', $statutJuryId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterAffectation($data) : null;
    }

    public function creer(AffectationEncadrant $affectation): bool
    {
        $data = [
            'utilisateur_id' => $affectation->getEnseignant()->getUtilisateurId(),
            'rapport_etudiant_id' => $affectation->getRapportEtudiant()->getId(),
            'statut_jury_id' => $affectation->getStatutJury()->getId(),
            'date_affectation' => $affectation->getDateAffectation()
        ];
        return parent::creer($data);
    }

    public function mettreAJour(AffectationEncadrant $affectation): bool
    {
        // Seule date_affectation peut être mise à jour. La CPK ne change pas.
        $sql = "UPDATE $this->table
                SET date_affectation = :date_affectation
                WHERE utilisateur_id = :utilisateur_id
                  AND rapport_etudiant_id = :rapport_etudiant_id
                  AND statut_jury_id = :statut_jury_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date_affectation', $affectation->getDateAffectation());
        $stmt->bindValue(':utilisateur_id', $affectation->getEnseignant()->getUtilisateurId());
        $stmt->bindValue(':rapport_etudiant_id', $affectation->getRapportEtudiant()->getId());
        $stmt->bindValue(':statut_jury_id', $affectation->getStatutJury()->getId());
        return $stmt->execute();
    }

    public function supprimerParIdsComposites(string $utilisateurId, string $rapportEtudiantId, string $statutJuryId): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE utilisateur_id = :utilisateur_id
                  AND rapport_etudiant_id = :rapport_etudiant_id
                  AND statut_jury_id = :statut_jury_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateurId);
        $stmt->bindParam(':rapport_etudiant_id', $rapportEtudiantId);
        $stmt->bindParam(':statut_jury_id', $statutJuryId);
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

        $enseignantIds = array_unique(array_column($resultsData, 'utilisateur_id'));
        $rapportIds = array_unique(array_column($resultsData, 'rapport_etudiant_id'));
        $statutJuryIds = array_unique(array_column($resultsData, 'statut_jury_id'));

        $enseignants = !empty($enseignantIds) ? $this->enseignantDAO->rechercher(['utilisateur_id' => $enseignantIds]) : [];
        $rapports = !empty($rapportIds) ? $this->rapportEtudiantDAO->rechercher(['id' => $rapportIds]) : [];
        $statutsJury = !empty($statutJuryIds) ? $this->statutJuryDAO->rechercher(['id' => $statutJuryIds]) : [];

        $enseignantsMap = []; foreach ($enseignants as $e) { $enseignantsMap[$e->getUtilisateurId()] = $e; }
        $rapportsMap = []; foreach ($rapports as $r) { $rapportsMap[$r->getId()] = $r; }
        $statutsJuryMap = []; foreach ($statutsJury as $s) { $statutsJuryMap[$s->getId()] = $s; }

        $affectations = [];
        foreach ($resultsData as $data) {
            $enseignant = $enseignantsMap[$data['utilisateur_id']] ?? null;
            $rapport = $rapportsMap[$data['rapport_etudiant_id']] ?? null;
            $statut = $statutsJuryMap[$data['statut_jury_id']] ?? null;

            if ($enseignant && $rapport && $statut) {
                $affectations[] = new AffectationEncadrant(
                    $enseignant,
                    $rapport,
                    $statut,
                    $data['date_affectation'] ?? null
                );
            }
        }
        return $affectations;
    }
}