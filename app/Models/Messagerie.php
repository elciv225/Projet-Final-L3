<?php

namespace App\Models;

class Messagerie
{
    private string $membre_commission_id;
    private string $etudiant_concerne_id;
    private string $discussion_id;
    private string $message;
    private string $date_message;

    /**
     * @return string
     */
    public function getMembreCommissionId(): string
    {
        return $this->membre_commission_id;
    }

    /**
     * @param string $membre_commission_id
     */
    public function setMembreCommissionId(string $membre_commission_id): void
    {
        $this->membre_commission_id = $membre_commission_id;
    }

    /**
     * @return string
     */
    public function getEtudiantConcerneId(): string
    {
        return $this->etudiant_concerne_id;
    }

    /**
     * @param string $etudiant_concerne_id
     */
    public function setEtudiantConcerneId(string $etudiant_concerne_id): void
    {
        $this->etudiant_concerne_id = $etudiant_concerne_id;
    }

    /**
     * @return string
     */
    public function getDiscussionId(): string
    {
        return $this->discussion_id;
    }

    /**
     * @param string $discussion_id
     */
    public function setDiscussionId(string $discussion_id): void
    {
        $this->discussion_id = $discussion_id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDateMessage(): string
    {
        return $this->date_message;
    }

    /**
     * @param string $date_message
     */
    public function setDateMessage(string $date_message): void
    {
        $this->date_message = $date_message;
    }
}