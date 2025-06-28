<?php

namespace App\Dao;

use PDO;
use App\Models\Audit;

class AuditDAO extends DAO
{
    private TraitementDAO $traitementDAO;
    private UtilisateurDAO $utilisateurDAO;

    // Constructeur Audit: __construct(Traitement $traitement, Utilisateur $utilisateur, ?string $description, ?string $dateTraitement, ?int $id = null)

    public function __construct(
        PDO $pdo,
        TraitementDAO $traitementDAO,
        UtilisateurDAO $utilisateurDAO
    ) {
        parent::__construct($pdo, 'audit', Audit::class, 'id');
        $this->traitementDAO = $traitementDAO;
        $this->utilisateurDAO = $utilisateurDAO;
    }

    private function hydraterAudit(array $data): ?Audit
    {
        $traitement = $this->traitementDAO->recupererParId($data['traitement_id']);
        $utilisateur = $this->utilisateurDAO->recupererParId($data['utilisateur_id']);

        if ($traitement && $utilisateur) {
            return new Audit(
                $traitement,
                $utilisateur,
                $data['description'] ?? null,
                $data['date_traitement'] ?? null, // TIMESTAMP
                $data['id'] ?? null
            );
        }
        return null;
    }

    // Surcharger recupererParId pour utiliser l'hydratation personnalisée
    public function recupererParId(string $id): ?Audit
    {
        $query = "SELECT * FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterAudit($data) : null;
    }

    // Surcharger creer pour extraire les IDs des objets
    public function creer(Audit $audit): bool
    {
        $data = [
            // 'id' est auto-incrémenté
            'description' => $audit->getDescription(),
            'date_traitement' => $audit->getDateTraitement(), // TIMESTAMP
            'traitement_id' => $audit->getTraitement()->getId(),
            'utilisateur_id' => $audit->getUtilisateur()->getId(),
        ];
        if ($data['date_traitement'] === null) { // Laisser la DB mettre le CURRENT_TIMESTAMP si applicable
            unset($data['date_traitement']);
        }
        return parent::creer($data);
    }

    // Surcharger mettreAJour pour extraire les IDs des objets
    public function mettreAJour(Audit $audit): bool
    {
        if ($audit->getId() === null) {
            return false;
        }
        $data = [
            'description' => $audit->getDescription(),
            'date_traitement' => $audit->getDateTraitement(),
            'traitement_id' => $audit->getTraitement()->getId(),
            'utilisateur_id' => $audit->getUtilisateur()->getId(),
        ];
        return parent::mettreAJour((string)$audit->getId(), $data);
    }

    public function recupererTousAvecObjets(?string $orderBy = 'date_traitement', ?string $orderType = 'DESC'): array
    {
        $query = "SELECT * FROM $this->table";
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) return [];

        $traitementIds = array_unique(array_column($resultsData, 'traitement_id'));
        $utilisateurIds = array_unique(array_column($resultsData, 'utilisateur_id'));

        $traitements = !empty($traitementIds) ? $this->traitementDAO->rechercher(['id' => $traitementIds]) : [];
        $utilisateurs = !empty($utilisateurIds) ? $this->utilisateurDAO->rechercher(['id' => $utilisateurIds]) : [];

        $traitementsMap = []; foreach ($traitements as $t) { $traitementsMap[$t->getId()] = $t; }
        $utilisateursMap = []; foreach ($utilisateurs as $u) { $utilisateursMap[$u->getId()] = $u; }

        $audits = [];
        foreach ($resultsData as $data) {
            $auditInstance = $this->hydraterAudit($data); // Utilise l'ID de l'audit déjà présent dans $data
            if ($auditInstance) {
                $audits[] = $auditInstance;
            }
        }
        return $audits;
    }
}