<?php

namespace App\Dao;

use PDO;
use App\Models\AuditAction;

class AuditActionDAO extends DAO
{
    private AuditDAO $auditDAO;
    private ActionDAO $actionDAO;

    // Constructeur AuditAction: __construct(Audit $audit, Action $action, string $statut, ?int $ordre = null, ...)

    public function __construct(
        PDO $pdo,
        AuditDAO $auditDAO,
        ActionDAO $actionDAO
    ) {
        parent::__construct($pdo, 'audit_action', AuditAction::class, 'id'); // Clé primaire simple 'id'
        $this->auditDAO = $auditDAO;
        $this->actionDAO = $actionDAO;
    }

    /**
     * Hydrate un objet AuditAction à partir d'un tableau de données brutes.
     * @param array $data Données de la table audit_action.
     * @return AuditAction|null
     */
    private function hydraterAuditAction(array $data): ?AuditAction
    {
        $audit = $this->auditDAO->recupererParId($data['audit_id']);
        $action = $this->actionDAO->recupererParId($data['action_id']);

        if ($audit && $action) {
            // La méthode recupererParId de DAO.php utilise PDO::FETCH_CLASS,
            // ce qui signifie qu'elle essaiera d'appeler le constructeur du modèle AuditAction
            // avec les colonnes de la table. Cela ne fonctionnera pas car le constructeur attend des objets.
            // Donc, nous devons instancier manuellement.
            return new AuditAction(
                $audit,
                $action,
                $data['statut'],
                $data['ordre'] ?? null,
                $data['heure_execution'] ?? null,
                $data['message'] ?? null,
                $data['id'] // L'ID de l'enregistrement AuditAction lui-même
            );
        }
        return null;
    }

    /**
     * Récupère un AuditAction par son ID, avec les objets Audit et Action hydratés.
     * @param string $id
     * @return AuditAction|null
     */
    public function recupererParId(string $id): ?AuditAction // Surcharge la méthode du parent
    {
        $query = "SELECT * FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterAuditAction($data) : null;
    }


    /**
     * Crée un nouvel enregistrement audit_action.
     * @param AuditAction $auditAction
     * @return bool True si la création a réussi, false sinon.
     */
    public function creer(AuditAction $auditAction): bool // Surcharge la méthode du parent
    {
        $data = [
            // 'id' est auto-incrémenté
            'audit_id' => $auditAction->getAudit()->getId(),
            'action_id' => $auditAction->getAction()->getId(),
            'ordre' => $auditAction->getOrdre(),
            'heure_execution' => $auditAction->getHeureExecution(), // Peut être null si la DB gère le DEFAULT
            'statut' => $auditAction->getStatut(),
            'message' => $auditAction->getMessage()
        ];

        // Si l'heure d'exécution n'est pas fournie et que la DB a un DEFAULT, on peut l'omettre
        if ($data['heure_execution'] === null) {
            unset($data['heure_execution']);
        }

        return parent::creer($data); // Appelle la méthode creer de DAO.php
    }

    /**
     * Met à jour un enregistrement audit_action.
     * @param AuditAction $auditAction L'objet AuditAction à mettre à jour (doit avoir un ID).
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function mettreAJour(AuditAction $auditAction): bool // Surcharge la méthode du parent
    {
        if ($auditAction->getId() === null) {
            return false; // Impossible de mettre à jour sans ID
        }

        $data = [
            'audit_id' => $auditAction->getAudit()->getId(),
            'action_id' => $auditAction->getAction()->getId(),
            'ordre' => $auditAction->getOrdre(),
            'heure_execution' => $auditAction->getHeureExecution(),
            'statut' => $auditAction->getStatut(),
            'message' => $auditAction->getMessage()
        ];
        return parent::mettreAJour((string)$auditAction->getId(), $data); // Appelle la méthode mettreAJour de DAO.php
    }

    /**
     * Récupère tous les AuditAction avec leurs objets Audit et Action hydratés.
     */
    public function recupererTousAvecObjets(?string $orderBy = 'heure_execution', ?string $orderType = 'DESC'): array
    {
        $query = "SELECT * FROM $this->table";
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) return [];

        $auditIds = array_unique(array_column($resultsData, 'audit_id'));
        $actionIds = array_unique(array_column($resultsData, 'action_id'));

        $audits = !empty($auditIds) ? $this->auditDAO->rechercher(['id' => $auditIds]) : [];
        $actions = !empty($actionIds) ? $this->actionDAO->rechercher(['id' => $actionIds]) : [];

        $auditsMap = []; foreach ($audits as $a) { $auditsMap[$a->getId()] = $a; }
        $actionsMap = []; foreach ($actions as $a) { $actionsMap[$a->getId()] = $a; }

        $auditActions = [];
        foreach ($resultsData as $data) {
            $audit = $auditsMap[$data['audit_id']] ?? null;
            $action = $actionsMap[$data['action_id']] ?? null;

            if ($audit && $action) {
                 // Utilisation de la méthode d'hydratation pour construire l'objet
                $auditActionInstance = $this->hydraterAuditAction($data);
                if ($auditActionInstance) {
                    $auditActions[] = $auditActionInstance;
                }
            }
        }
        return $auditActions;
    }
}
