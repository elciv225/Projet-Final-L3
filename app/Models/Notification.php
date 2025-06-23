<?php

namespace App\Models;

/**
 * Class Notification
 *
 * @package App\Models
 */
class Notification
{
    /**
     * @var string
     */
    protected string $table = 'notification';

    /**
     * @var string L'ID de l'émetteur (FK, partie de la CPK).
     */
    private string $emetteurId;

    /**
     * @var string L'ID du récepteur (FK, partie de la CPK).
     */
    private string $recepteurId;

    /**
     * @var string|null Le contenu du message.
     */
    private ?string $message; // DDL spécifie TEXT

    /**
     * @var string La date de la notification (partie de la CPK).
     */
    private string $dateNotification; // DDL spécifie DATETIME

    /**
     * @param string $emetteurId
     * @param string $recepteurId
     * @param string|null $message
     * @param string $dateNotification
     */
    public function __construct(string $emetteurId, string $recepteurId, ?string $message, string $dateNotification)
    {
        $this->emetteurId = $emetteurId;
        $this->recepteurId = $recepteurId;
        $this->message = $message;
        $this->dateNotification = $dateNotification;
    }

    /**
     * @return string
     */
    public function getEmetteurId(): string
    {
        return $this->emetteurId;
    }

    /**
     * @param string $emetteurId
     */
    public function setEmetteurId(string $emetteurId): void
    {
        $this->emetteurId = $emetteurId;
    }

    /**
     * @return string
     */
    public function getRecepteurId(): string
    {
        return $this->recepteurId;
    }

    /**
     * @param string $recepteurId
     */
    public function setRecepteurId(string $recepteurId): void
    {
        $this->recepteurId = $recepteurId;
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
    public function getDateNotification(): string
    {
        return $this->dateNotification;
    }

    /**
     * @param string $dateNotification
     */
    public function setDateNotification(string $dateNotification): void
    {
        $this->dateNotification = $dateNotification;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
