<?php

namespace App\Models;

/**
 * Class Messagerie
 *
 * @package App\Models
 */
class Messagerie
{
    /**
     * @var string
     */
    protected string $table = 'messagerie';

    /**
     * @var string ID du membre de la commission (FK vers enseignant.utilisateur_id, partie de la CPK).
     */
    private string $membreCommissionId;

    /**
     * @var string ID de l'étudiant concerné (FK vers etudiant.utilisateur_id, partie de la CPK).
     */
    private string $etudiantConcerneId;

    /**
     * @var string ID de la discussion (FK vers discussion.id, partie de la CPK).
     */
    private string $discussionId;

    /**
     * @var string|null Contenu du message.
     */
    private ?string $message; // DDL spécifie TEXT

    /**
     * @var string Date du message (partie de la CPK).
     */
    private string $dateMessage; // DDL spécifie DATETIME

    /**
     * @param string $membreCommissionId
     * @param string $etudiantConcerneId
     * @param string $discussionId
     * @param string|null $message
     * @param string $dateMessage
     */
    public function __construct(string $membreCommissionId, string $etudiantConcerneId, string $discussionId, ?string $message, string $dateMessage)
    {
        $this->membreCommissionId = $membreCommissionId;
        $this->etudiantConcerneId = $etudiantConcerneId;
        $this->discussionId = $discussionId;
        $this->message = $message;
        $this->dateMessage = $dateMessage;
    }

    /**
     * @return string
     */
    public function getMembreCommissionId(): string
    {
        return $this->membreCommissionId;
    }

    /**
     * @param string $membreCommissionId
     */
    public function setMembreCommissionId(string $membreCommissionId): void
    {
        $this->membreCommissionId = $membreCommissionId;
    }

    /**
     * @return string
     */
    public function getEtudiantConcerneId(): string
    {
        return $this->etudiantConcerneId;
    }

    /**
     * @param string $etudiantConcerneId
     */
    public function setEtudiantConcerneId(string $etudiantConcerneId): void
    {
        $this->etudiantConcerneId = $etudiantConcerneId;
    }

    /**
     * @return string
     */
    public function getDiscussionId(): string
    {
        return $this->discussionId;
    }

    /**
     * @param string $discussionId
     */
    public function setDiscussionId(string $discussionId): void
    {
        $this->discussionId = $discussionId;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDateMessage(): string
    {
        return $this->dateMessage;
    }

    /**
     * @param string $dateMessage
     */
    public function setDateMessage(string $dateMessage): void
    {
        $this->dateMessage = $dateMessage;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
