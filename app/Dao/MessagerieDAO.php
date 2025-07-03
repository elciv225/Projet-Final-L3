<?php

namespace App\Dao;

use PDO;
use App\Models\Messagerie;

class MessagerieDAO extends DAO
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'messagerie', Messagerie::class, 'membre_commission_id');
    }

    /**
     * Enregistre un nouveau message dans une discussion
     * @param string $membreCommissionId
     * @param string $etudiantConcerneId
     * @param string $discussionId
     * @param string $message
     * @param string $dateMessage
     * @return bool
     */
    public function enregistrerMessage(string $membreCommissionId, string $etudiantConcerneId, string $discussionId, string $message, string $dateMessage): bool
    {
        $sql = "INSERT INTO messagerie (membre_commission_id, etudiant_concerne_id, discussion_id, message, date_message) 
                VALUES (:membre_commission_id, :etudiant_concerne_id, :discussion_id, :message, :date_message)";
        
        return $this->executerRequeteAction($sql, [
            'membre_commission_id' => $membreCommissionId,
            'etudiant_concerne_id' => $etudiantConcerneId,
            'discussion_id' => $discussionId,
            'message' => $message,
            'date_message' => $dateMessage
        ]) > 0;
    }

    /**
     * Récupère tous les messages d'une discussion
     * @param string $discussionId
     * @return array
     */
    public function getMessagesDiscussion(string $discussionId): array
    {
        $sql = "SELECT m.*, u.nom as nom_membre, u.prenoms as prenoms_membre 
                FROM messagerie m
                JOIN utilisateur u ON m.membre_commission_id = u.id
                WHERE m.discussion_id = :discussion_id
                ORDER BY m.date_message ASC";
        
        return $this->executerSelect($sql, ['discussion_id' => $discussionId]);
    }

    /**
     * Récupère toutes les discussions concernant un étudiant
     * @param string $etudiantId
     * @return array
     */
    public function getDiscussionsEtudiant(string $etudiantId): array
    {
        $sql = "SELECT DISTINCT m.discussion_id, d.date_discussion, 
                       (SELECT COUNT(*) FROM messagerie WHERE discussion_id = m.discussion_id) as nb_messages,
                       e.nom as nom_etudiant, e.prenoms as prenoms_etudiant,
                       re.id as rapport_id, re.titre as titre_rapport
                FROM messagerie m
                JOIN discussion d ON m.discussion_id = d.id
                JOIN utilisateur e ON m.etudiant_concerne_id = e.id
                LEFT JOIN depot_rapport dr ON m.etudiant_concerne_id = dr.utilisateur_id
                LEFT JOIN rapport_etudiant re ON dr.rapport_etudiant_id = re.id
                WHERE m.etudiant_concerne_id = :etudiant_id
                ORDER BY d.date_discussion DESC";
        
        return $this->executerSelect($sql, ['etudiant_id' => $etudiantId]);
    }

    /**
     * Récupère toutes les discussions
     * @return array
     */
    public function getAllDiscussions(): array
    {
        $sql = "SELECT DISTINCT m.discussion_id, d.date_discussion, 
                       (SELECT COUNT(*) FROM messagerie WHERE discussion_id = m.discussion_id) as nb_messages,
                       e.id as etudiant_id, e.nom as nom_etudiant, e.prenoms as prenoms_etudiant,
                       re.id as rapport_id, re.titre as titre_rapport
                FROM messagerie m
                JOIN discussion d ON m.discussion_id = d.id
                JOIN utilisateur e ON m.etudiant_concerne_id = e.id
                LEFT JOIN depot_rapport dr ON m.etudiant_concerne_id = dr.utilisateur_id
                LEFT JOIN rapport_etudiant re ON dr.rapport_etudiant_id = re.id
                ORDER BY d.date_discussion DESC";
        
        return $this->executerSelect($sql);
    }

    /**
     * Crée une nouvelle discussion
     * @param string $discussionId
     * @param string $dateDiscussion
     * @return bool
     */
    public function creerDiscussion(string $discussionId, string $dateDiscussion): bool
    {
        $sql = "INSERT INTO discussion (id, date_discussion) VALUES (:id, :date_discussion)";
        
        return $this->executerRequeteAction($sql, [
            'id' => $discussionId,
            'date_discussion' => $dateDiscussion
        ]) > 0;
    }

    /**
     * Vérifie si une discussion existe
     * @param string $discussionId
     * @return bool
     */
    public function discussionExiste(string $discussionId): bool
    {
        $sql = "SELECT COUNT(*) as nb FROM discussion WHERE id = :id";
        $result = $this->executerSelect($sql, ['id' => $discussionId]);
        
        return isset($result[0]['nb']) && $result[0]['nb'] > 0;
    }
}