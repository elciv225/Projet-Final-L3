<?php

namespace App\Dao;

use PDO;
use App\Models\Messagerie;

class MessagerieDAO extends DAO
{
    private EnseignantDAO $enseignantDAO;
    private EtudiantDAO $etudiantDAO;
    private DiscussionDAO $discussionDAO;

    // CPK: membre_commission_id, etudiant_concerne_id, discussion_id, date_message
    // Constructeur Messagerie: __construct(Enseignant $membreCommission, Etudiant $etudiantConcerne, Discussion $discussion, ?string $message, string $dateMessage)

    public function __construct(
        PDO $pdo,
        EnseignantDAO $enseignantDAO,
        EtudiantDAO $etudiantDAO,
        DiscussionDAO $discussionDAO
    ) {
        parent::__construct($pdo, 'messagerie', Messagerie::class, ''); // Pas de clé primaire simple
        $this->enseignantDAO = $enseignantDAO;
        $this->etudiantDAO = $etudiantDAO;
        $this->discussionDAO = $discussionDAO;
    }

    private function hydraterMessagerie(array $data): ?Messagerie
    {
        $membreCommission = $this->enseignantDAO->recupererParId($data['membre_commission_id']);
        $etudiantConcerne = $this->etudiantDAO->recupererParId($data['etudiant_concerne_id']);
        $discussion = $this->discussionDAO->recupererParId($data['discussion_id']);

        if ($membreCommission && $etudiantConcerne && $discussion) {
            return new Messagerie(
                $membreCommission,
                $etudiantConcerne,
                $discussion,
                $data['message'] ?? null,
                $data['date_message'] // DATETIME, doit être une chaîne
            );
        }
        return null;
    }

    public function recupererParIdsComposites(string $membreCommissionId, string $etudiantConcerneId, string $discussionId, string $dateMessage): ?Messagerie
    {
        $query = "SELECT * FROM $this->table
                  WHERE membre_commission_id = :membre_commission_id
                    AND etudiant_concerne_id = :etudiant_concerne_id
                    AND discussion_id = :discussion_id
                    AND date_message = :date_message";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':membre_commission_id', $membreCommissionId);
        $stmt->bindParam(':etudiant_concerne_id', $etudiantConcerneId);
        $stmt->bindParam(':discussion_id', $discussionId);
        $stmt->bindParam(':date_message', $dateMessage); // DATETIME
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydraterMessagerie($data) : null;
    }

    public function creer(Messagerie $messagerie): bool
    {
        $data = [
            'membre_commission_id' => $messagerie->getMembreCommission()->getUtilisateurId(),
            'etudiant_concerne_id' => $messagerie->getEtudiantConcerne()->getUtilisateurId(),
            'discussion_id' => $messagerie->getDiscussion()->getId(),
            'message' => $messagerie->getMessage(),
            'date_message' => $messagerie->getDateMessage() // DATETIME
        ];
        return parent::creer($data);
    }

    // Une mise à jour d'un message de messagerie est peu probable (on supprime et recrée, ou on ajoute un nouveau message).
    // Si la mise à jour du contenu du message est permise pour la même date_message (qui fait partie de la clé):
    public function mettreAJourContenuMessage(Messagerie $messagerie): bool
    {
        $sql = "UPDATE $this->table
                SET message = :message
                WHERE membre_commission_id = :membre_commission_id
                  AND etudiant_concerne_id = :etudiant_concerne_id
                  AND discussion_id = :discussion_id
                  AND date_message = :date_message";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':message', $messagerie->getMessage());
        $stmt->bindValue(':membre_commission_id', $messagerie->getMembreCommission()->getUtilisateurId());
        $stmt->bindValue(':etudiant_concerne_id', $messagerie->getEtudiantConcerne()->getUtilisateurId());
        $stmt->bindValue(':discussion_id', $messagerie->getDiscussion()->getId());
        $stmt->bindValue(':date_message', $messagerie->getDateMessage());
        return $stmt->execute();
    }

    public function supprimerParIdsComposites(string $membreCommissionId, string $etudiantConcerneId, string $discussionId, string $dateMessage): bool
    {
        $sql = "DELETE FROM $this->table
                WHERE membre_commission_id = :membre_commission_id
                  AND etudiant_concerne_id = :etudiant_concerne_id
                  AND discussion_id = :discussion_id
                  AND date_message = :date_message";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':membre_commission_id', $membreCommissionId);
        $stmt->bindParam(':etudiant_concerne_id', $etudiantConcerneId);
        $stmt->bindParam(':discussion_id', $discussionId);
        $stmt->bindParam(':date_message', $dateMessage);
        return $stmt->execute();
    }

    public function recupererTousAvecObjets(?string $orderBy = 'date_message', ?string $orderType = 'DESC'): array
    {
        $query = "SELECT * FROM $this->table";
        if ($orderBy && $orderType) {
            $query .= " ORDER BY $orderBy $orderType";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $resultsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultsData)) return [];

        $membreCommissionIds = array_unique(array_column($resultsData, 'membre_commission_id'));
        $etudiantConcerneIds = array_unique(array_column($resultsData, 'etudiant_concerne_id'));
        $discussionIds = array_unique(array_column($resultsData, 'discussion_id'));

        $membres = !empty($membreCommissionIds) ? $this->enseignantDAO->rechercher(['utilisateur_id' => $membreCommissionIds]) : [];
        $etudiants = !empty($etudiantConcerneIds) ? $this->etudiantDAO->rechercher(['utilisateur_id' => $etudiantConcerneIds]) : [];
        $discussions = !empty($discussionIds) ? $this->discussionDAO->rechercher(['id' => $discussionIds]) : [];

        $membresMap = []; foreach ($membres as $m) { $membresMap[$m->getUtilisateurId()] = $m; }
        $etudiantsMap = []; foreach ($etudiants as $e) { $etudiantsMap[$e->getUtilisateurId()] = $e; }
        $discussionsMap = []; foreach ($discussions as $d) { $discussionsMap[$d->getId()] = $d; }

        $messageries = [];
        foreach ($resultsData as $data) {
            $membre = $membresMap[$data['membre_commission_id']] ?? null;
            $etudiant = $etudiantsMap[$data['etudiant_concerne_id']] ?? null;
            $discussion = $discussionsMap[$data['discussion_id']] ?? null;

            if ($membre && $etudiant && $discussion) {
                $messageries[] = new Messagerie(
                    $membre,
                    $etudiant,
                    $discussion,
                    $data['message'] ?? null,
                    $data['date_message']
                );
            }
        }
        return $messageries;
    }
}