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
     * Le membre de la commission (enseignant) ayant envoyé le message.
     * @var Enseignant
     */
    private Enseignant $membreCommission;

    /**
     * L'étudiant concerné par le message.
     * @var Etudiant
     */
    private Etudiant $etudiantConcerne;

    /**
     * La discussion à laquelle ce message appartient.
     * @var Discussion
     */
    private Discussion $discussion;

    /**
     * @var string|null Contenu du message.
     */
    private ?string $message; // DDL spécifie TEXT

    /**
     * @var string Date du message (partie de la CPK).
     */
    private string $dateMessage; // DDL spécifie DATETIME

    /**
     * @param Enseignant $membreCommission
     * @param Etudiant $etudiantConcerne
     * @param Discussion $discussion
     * @param string|null $message
     * @param string $dateMessage
     */
    public function __construct(
        Enseignant $membreCommission,
        Etudiant $etudiantConcerne,
        Discussion $discussion,
        ?string $message,
        string $dateMessage
    ) {
        $this->membreCommission = $membreCommission;
        $this->etudiantConcerne = $etudiantConcerne;
        $this->discussion = $discussion;
        $this->message = $message;
        $this->dateMessage = $dateMessage;
    }

    /**
     * @return Enseignant
     */
    public function getMembreCommission(): Enseignant
    {
        return $this->membreCommission;
    }

    /**
     * @param Enseignant $membreCommission
     */
    public function setMembreCommission(Enseignant $membreCommission): void
    {
        $this->membreCommission = $membreCommission;
    }

    /**
     * @return Etudiant
     */
    public function getEtudiantConcerne(): Etudiant
    {
        return $this->etudiantConcerne;
    }

    /**
     * @param Etudiant $etudiantConcerne
     */
    public function setEtudiantConcerne(Etudiant $etudiantConcerne): void
    {
        $this->etudiantConcerne = $etudiantConcerne;
    }

    /**
     * @return Discussion
     */
    public function getDiscussion(): Discussion
    {
        return $this->discussion;
    }

    /**
     * @param Discussion $discussion
     */
    public function setDiscussion(Discussion $discussion): void
    {
        $this->discussion = $discussion;
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
