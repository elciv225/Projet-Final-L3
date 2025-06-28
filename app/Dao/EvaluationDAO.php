<?php

namespace App\Dao;

use PDO;
use App\Models\Evaluation;

class EvaluationDAO extends DAO
{
    private EnseignantDAO $enseignantDAO;
    private EtudiantDAO $etudiantDAO;
    private EcueDAO $ecueDAO;

    // CPK: enseignant_id, etudiant_id, ecue_id
    // Constructeur Evaluation: __construct(Enseignant $enseignant, Etudiant $etudiant, Ecue $ecue, ?string $dateEvaluation, ?int $note)

    public function __construct(
        PDO $pdo,
        EnseignantDAO $enseignantDAO,
        EtudiantDAO $etudiantDAO,
        EcueDAO $ecueDAO
    ) {
        parent::__construct($pdo, 'evaluation', Evaluation::class, ''); // Pas de clé primaire simple
        $this->enseignantDAO = $enseignantDAO;
        $this->etudiantDAO = $etudiantDAO;
        $this->ecueDAO = $ecueDAO;
    }

    private function hydraterEvaluation(array $data): ?Evaluation
    {
        $enseignant = $this->enseignantDAO->recupererParId($data['enseignant_id']);
        $etudiant = $this->etudiantDAO->recupererParId($data['etudiant_id']);
        $ecue = $this->ecueDAO->recupererParId($data['ecue_id']);

        if ($enseignant && $etudiant && $ecue) {
            return new Evaluation(
                $enseignant,
                $etudiant,
                $ecue,
                $data['date_evaluation'] ?? null,
                isset($data['note']) ? (int)$data['note'] : null
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $enseignantId, string $etudiantId, string $ecueId): ?Evaluation
    {
        $query = "SELECT * FROM $this->table
                  WHERE enseignant_id = :enseignant_id
                    AND etudiant_id = :etudiant_id
                    AND ecue_id = :ecue_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':enseignant_id', $enseignantId);
        $stmt->bindParam(':etudiant_id', $etudiantId);
        $stmt->bindParam(':ecue_id', $ecueId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterEvaluation($data) : null;
    }

    public function creer(Evaluation $evaluation): bool
    {
        $data = [
            'enseignant_id' => $evaluation->getEnseignant()->getUtilisateurId(),
            'etudiant_id' => $evaluation->getEtudiant()->getUtilisateurId(),
            'ecue_id' => $evaluation->getEcue()->getId(),
            'date_evaluation' => $evaluation->getDateEvaluation(),
            'note' => $evaluation->getNote()
        ];
        return parent::creer($data);
    }

    public function mettreAJour(Evaluation $evaluation): bool
    {
        // Seuls date_evaluation et note peuvent être mis à jour. La CPK ne change pas.
        $sql = "UPDATE $this->table
                SET date_evaluation = :date_evaluation, note = :note
                WHERE enseignant_id = :enseignant_id
                  AND etudiant_id = :etudiant_id
                  AND ecue_id = :ecue_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date_evaluation', $evaluation->getDateEvaluation());
        $stmt->bindValue(':note', $evaluation->getNote());
        $stmt->bindValue(':enseignant_id', $evaluation->getEnseignant()->getUtilisateurId());
        $stmt->bindValue(':etudiant_id', $evaluation->getEtudiant()->getUtilisateurId());
        $stmt->bindValue(':ecue_id', $evaluation->getEcue()->getId());
        return $stmt->execute();
    }

    public function supprimerParIdsComposites(string $enseignantId, string $etudiantId, string $ecueId): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE enseignant_id = :enseignant_id
                  AND etudiant_id = :etudiant_id
                  AND ecue_id = :ecue_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':enseignant_id', $enseignantId);
        $stmt->bindParam(':etudiant_id', $etudiantId);
        $stmt->bindParam(':ecue_id', $ecueId);
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

        $enseignantIds = array_unique(array_column($resultsData, 'enseignant_id'));
        $etudiantIds = array_unique(array_column($resultsData, 'etudiant_id'));
        $ecueIds = array_unique(array_column($resultsData, 'ecue_id'));

        $enseignants = !empty($enseignantIds) ? $this->enseignantDAO->rechercher(['utilisateur_id' => $enseignantIds]) : [];
        $etudiants = !empty($etudiantIds) ? $this->etudiantDAO->rechercher(['utilisateur_id' => $etudiantIds]) : [];
        $ecues = !empty($ecueIds) ? $this->ecueDAO->rechercher(['id' => $ecueIds]) : [];

        $enseignantsMap = []; foreach ($enseignants as $e) { $enseignantsMap[$e->getUtilisateurId()] = $e; }
        $etudiantsMap = []; foreach ($etudiants as $e) { $etudiantsMap[$e->getUtilisateurId()] = $e; }
        $ecuesMap = []; foreach ($ecues as $e) { $ecuesMap[$e->getId()] = $e; }

        $evaluations = [];
        foreach ($resultsData as $data) {
            $enseignant = $enseignantsMap[$data['enseignant_id']] ?? null;
            $etudiant = $etudiantsMap[$data['etudiant_id']] ?? null;
            $ecue = $ecuesMap[$data['ecue_id']] ?? null;

            if ($enseignant && $etudiant && $ecue) {
                $evaluations[] = new Evaluation(
                    $enseignant,
                    $etudiant,
                    $ecue,
                    $data['date_evaluation'] ?? null,
                    isset($data['note']) ? (int)$data['note'] : null
                );
            }
        }
        return $evaluations;
    }
}