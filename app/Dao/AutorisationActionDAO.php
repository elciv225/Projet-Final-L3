<?php

namespace App\Dao;

use PDO;
use App\Models\AutorisationAction;

class AutorisationActionDAO extends DAO
{
    private GroupeUtilisateurDAO $groupeUtilisateurDAO;
    private TraitementDAO $traitementDAO;
    private ActionDAO $actionDAO;

    // CPK: groupe_utilisateur_id, traitement_id, action_id
    // Constructeur AutorisationAction: __construct(GroupeUtilisateur $groupeUtilisateur, Traitement $traitement, Action $action)

    public function __construct(
        PDO $pdo,
        GroupeUtilisateurDAO $groupeUtilisateurDAO,
        TraitementDAO $traitementDAO,
        ActionDAO $actionDAO
    ) {
        parent::__construct($pdo, 'autorisation_action', AutorisationAction::class, ''); // Pas de clé primaire simple
        $this->groupeUtilisateurDAO = $groupeUtilisateurDAO;
        $this->traitementDAO = $traitementDAO;
        $this->actionDAO = $actionDAO;
    }

    private function hydraterAutorisationAction(array $data): ?AutorisationAction
    {
        $groupeUtilisateur = $this->groupeUtilisateurDAO->recupererParId($data['groupe_utilisateur_id']);
        $traitement = $this->traitementDAO->recupererParId($data['traitement_id']);
        $action = $this->actionDAO->recupererParId($data['action_id']);

        if ($groupeUtilisateur && $traitement && $action) {
            return new AutorisationAction(
                $groupeUtilisateur,
                $traitement,
                $action
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $groupeUtilisateurId, string $traitementId, string $actionId): ?AutorisationAction
    {
        $query = "SELECT * FROM $this->table
                  WHERE groupe_utilisateur_id = :groupe_utilisateur_id
                    AND traitement_id = :traitement_id
                    AND action_id = :action_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':groupe_utilisateur_id', $groupeUtilisateurId);
        $stmt->bindParam(':traitement_id', $traitementId);
        $stmt->bindParam(':action_id', $actionId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterAutorisationAction($data) : null;
    }

    public function creer(AutorisationAction $autorisationAction): bool
    {
        $data = [
            'groupe_utilisateur_id' => $autorisationAction->getGroupeUtilisateur()->getId(),
            'traitement_id' => $autorisationAction->getTraitement()->getId(),
            'action_id' => $autorisationAction->getAction()->getId(),
        ];
        // La table autorisation_action n'a pas d'autres colonnes que sa CPK.
        // La méthode creer de DAO parent est donc directement utilisable.
        return parent::creer($data);
    }

    // Une mise à jour n'a pas de sens pour cette table, car toutes ses colonnes font partie de la clé primaire.
    // Pour modifier une autorisation, il faut la supprimer puis la recréer.

    public function supprimerParIdsComposites(string $groupeUtilisateurId, string $traitementId, string $actionId): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE groupe_utilisateur_id = :groupe_utilisateur_id
                  AND traitement_id = :traitement_id
                  AND action_id = :action_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':groupe_utilisateur_id', $groupeUtilisateurId);
        $stmt->bindParam(':traitement_id', $traitementId);
        $stmt->bindParam(':action_id', $actionId);
        return $stmt->execute();
    }

    public function recupererTousAvecObjets(?string $orderBy = null, ?string $orderType = null): array
    {
        $query = "SELECT * FROM $this->table"; // Pas d'autres colonnes que la CPK
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) return [];

        $groupeUtilisateurIds = array_unique(array_column($resultsData, 'groupe_utilisateur_id'));
        $traitementIds = array_unique(array_column($resultsData, 'traitement_id'));
        $actionIds = array_unique(array_column($resultsData, 'action_id'));

        $groupes = !empty($groupeUtilisateurIds) ? $this->groupeUtilisateurDAO->rechercher(['id' => $groupeUtilisateurIds]) : [];
        $traitements = !empty($traitementIds) ? $this->traitementDAO->rechercher(['id' => $traitementIds]) : [];
        $actions = !empty($actionIds) ? $this->actionDAO->rechercher(['id' => $actionIds]) : [];

        $groupesMap = []; foreach ($groupes as $g) { $groupesMap[$g->getId()] = $g; }
        $traitementsMap = []; foreach ($traitements as $t) { $traitementsMap[$t->getId()] = $t; }
        $actionsMap = []; foreach ($actions as $a) { $actionsMap[$a->getId()] = $a; }

        $autorisations = [];
        foreach ($resultsData as $data) {
            $groupe = $groupesMap[$data['groupe_utilisateur_id']] ?? null;
            $traitement = $traitementsMap[$data['traitement_id']] ?? null;
            $action = $actionsMap[$data['action_id']] ?? null;

            if ($groupe && $traitement && $action) {
                $autorisations[] = new AutorisationAction(
                    $groupe,
                    $traitement,
                    $action
                );
            }
        }
        return $autorisations;
    }
}