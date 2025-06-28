<?php

namespace App\Dao;

use PDO;
use App\Models\TraitementAction;
use App\Models\Traitement;
use App\Models\Action;

class TraitementActionDAO extends DAO
{
    private TraitementDAO $traitementDAO;
    private ActionDAO $actionDAO;

    public function __construct(PDO $pdo, TraitementDAO $traitementDAO, ActionDAO $actionDAO)
    {
        parent::__construct($pdo, 'traitement_action', TraitementAction::class, ''); // Pas de clé primaire simple
        $this->traitementDAO = $traitementDAO;
        $this->actionDAO = $actionDAO;
    }

    /**
     * Hydrate un objet TraitementAction à partir d'un tableau de données brutes.
     *
     * @param array $data Les données de la table traitement_action (traitement_id, action_id, ordre).
     * @return TraitementAction|null
     */
    private function hydraterTraitementAction(array $data): ?TraitementAction
    {
        $traitement = $this->traitementDAO->recupererParId($data['traitement_id']);
        $action = $this->actionDAO->recupererParId($data['action_id']);

        if ($traitement && $action) {
            // Le constructeur du modèle attend des objets, pas des arrays
            // et la classe DAO parente setFetchMode(PDO::FETCH_CLASS, $this->model)
            // tente d'appeler le constructeur avec les colonnes de la table.
            // Nous devons donc instancier manuellement ici après avoir récupéré les objets liés.
            return new TraitementAction($traitement, $action, $data['ordre'] ?? null);
        }
        return null;
    }

    /**
     * Récupère une liaison TraitementAction par ses identifiants composites.
     *
     * @param string $traitementId
     * @param string $actionId
     * @return TraitementAction|null
     */
    public function recupererParIdsComposites(string $traitementId, string $actionId): ?TraitementAction
    {
        $query = "SELECT * FROM $this->table WHERE traitement_id = :traitement_id AND action_id = :action_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':traitement_id', $traitementId);
        $stmt->bindParam(':action_id', $actionId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterTraitementAction($data) : null;
    }

    /**
     * Récupère toutes les liaisons TraitementAction avec leurs objets Traitement et Action.
     * Gère le problème N+1 en chargeant d'abord les IDs, puis les objets liés en batch.
     *
     * @param string|null $orderBy
     * @param string|null $orderType
     * @return TraitementAction[]
     */
    public function recupererTousAvecObjets(?string $orderBy = null, ?string $orderType = null): array
    {
        $query = "SELECT * FROM $this->table";
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) {
            return [];
        }

        // Collecter tous les IDs nécessaires
        $traitementIds = array_unique(array_column($resultsData, 'traitement_id'));
        $actionIds = array_unique(array_column($resultsData, 'action_id'));

        // Récupérer les objets liés en batch
        // Assumer que la méthode rechercher du DAO parent peut gérer un array d'IDs pour une clé.
        // Exemple: $criteres = ['id' => [1, 2, 3]] devrait générer WHERE id IN (1,2,3)
        $traitements = !empty($traitementIds) ? $this->traitementDAO->rechercher(['id' => $traitementIds]) : [];
        $actions = !empty($actionIds) ? $this->actionDAO->rechercher(['id' => $actionIds]) : [];

        // Mapper les objets par ID pour un accès facile
        $traitementsMap = [];
        foreach ($traitements as $t) {
            $traitementsMap[$t->getId()] = $t;
        }
        $actionsMap = [];
        foreach ($actions as $a) {
            $actionsMap[$a->getId()] = $a;
        }

        $traitementActions = [];
        foreach ($resultsData as $data) {
            $traitement = $traitementsMap[$data['traitement_id']] ?? null;
            $action = $actionsMap[$data['action_id']] ?? null;

            if ($traitement && $action) {
                $traitementActions[] = new TraitementAction($traitement, $action, $data['ordre'] ?? null);
            }
        }
        return $traitementActions;
    }


    /**
     * Crée une nouvelle liaison TraitementAction.
     *
     * @param TraitementAction $traitementAction
     * @return bool
     */
    public function creer(TraitementAction $traitementAction): bool
    {
        $data = [
            'traitement_id' => $traitementAction->getTraitement()->getId(),
            'action_id' => $traitementAction->getAction()->getId(),
            'ordre' => $traitementAction->getOrdre()
        ];
        // Utilise la méthode creer de la classe DAO parente
        return parent::creer($data);
    }

    /**
     * Met à jour l'ordre d'une liaison TraitementAction existante.
     * La clé primaire (Traitement, Action) ne peut pas être modifiée, seul l'attribut 'ordre'.
     *
     * @param TraitementAction $traitementAction
     * @return bool
     */
    public function mettreAJour(TraitementAction $traitementAction): bool
    {
        $sql = "UPDATE $this->table SET ordre = :ordre
                WHERE traitement_id = :traitement_id AND action_id = :action_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ordre', $traitementAction->getOrdre());
        $stmt->bindValue(':traitement_id', $traitementAction->getTraitement()->getId());
        $stmt->bindValue(':action_id', $traitementAction->getAction()->getId());
        return $stmt->execute();
    }

    /**
     * Supprime une liaison TraitementAction par les objets Traitement et Action.
     *
     * @param Traitement $traitement
     * @param Action $action
     * @return bool
     */
    public function supprimerParObjets(Traitement $traitement, Action $action): bool
    {
        $sql = "DELETE FROM $this->table WHERE traitement_id = :traitement_id AND action_id = :action_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':traitement_id', $traitement->getId());
        $stmt->bindValue(':action_id', $action->getId());
        return $stmt->execute();
    }

    /**
     * Supprime une liaison TraitementAction par ses identifiants composites.
     * (Méthode conservée pour compatibilité si on a que les IDs)
     *
     * @param string $traitementId
     * @param string $actionId
     * @return bool
     */
    public function supprimerParIds(string $traitementId, string $actionId): bool
    {
        $sql = "DELETE FROM $this->table WHERE traitement_id = :traitement_id AND action_id = :action_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':traitement_id', $traitementId);
        $stmt->bindValue(':action_id', $actionId);
        return $stmt->execute();
    }
}
