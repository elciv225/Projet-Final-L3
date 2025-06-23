<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Utilisateur;
use PDO;

/**
 * Class Notification
 *
 * Represents the notification table.
 * Composite PK: (emetteur_id, recepteur_id, date_notification).
 *
 * @package App\Models
 */
class Notification extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'notification';

    /**
     * @var string The ID of the sender (FK to utilisateur.id, part of CPK).
     */
    public string $emetteur_id;

    /**
     * @var string The ID of the receiver (FK to utilisateur.id, part of CPK).
     */
    public string $recepteur_id;

    /**
     * @var string|null The message content.
     */
    public ?string $message; // DDL TEXT

    /**
     * @var string The date of the notification (part of CPK).
     */
    public string $date_notification; // DDL DATETIME

    /**
     * Notification constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
