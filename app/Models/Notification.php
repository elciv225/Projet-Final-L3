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
     * L'utilisateur émetteur de la notification.
     * @var Utilisateur
     */
    private Utilisateur $emetteur;

    /**
     * L'utilisateur récepteur de la notification.
     * @var Utilisateur
     */
    private Utilisateur $recepteur;

    /**
     * @var string|null Le contenu du message.
     */
    private ?string $message; // DDL spécifie TEXT

    /**
     * @var string La date de la notification (partie de la CPK). Format TIMESTAMP.
     */
    private string $dateNotification;

    /**
     * Indique si la notification a été lue.
     * @var bool
     */
    private bool $lu = false;


    /**
     * @param Utilisateur $emetteur
     * @param Utilisateur $recepteur
     * @param string $dateNotification
     * @param string|null $message
     * @param bool $lu
     */
    public function __construct(
        Utilisateur $emetteur,
        Utilisateur $recepteur,
        string $dateNotification, // CPK, donc non nullable
        ?string $message,
        bool $lu = false
    ) {
        $this->emetteur = $emetteur;
        $this->recepteur = $recepteur;
        $this->dateNotification = $dateNotification;
        $this->message = $message;
        $this->lu = $lu;
    }

    /**
     * @return Utilisateur
     */
    public function getEmetteur(): Utilisateur
    {
        return $this->emetteur;
    }

    /**
     * @param Utilisateur $emetteur
     */
    public function setEmetteur(Utilisateur $emetteur): void
    {
        $this->emetteur = $emetteur;
    }

    /**
     * @return Utilisateur
     */
    public function getRecepteur(): Utilisateur
    {
        return $this->recepteur;
    }

    /**
     * @param Utilisateur $recepteur
     */
    public function setRecepteur(Utilisateur $recepteur): void
    {
        $this->recepteur = $recepteur;
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
     * @return bool
     */
    public function isLu(): bool
    {
        return $this->lu;
    }

    /**
     * @param bool $lu
     */
    public function setLu(bool $lu): void
    {
        $this->lu = $lu;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
