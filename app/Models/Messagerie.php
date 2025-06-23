<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Discussion;
use PDO;

/**
 * Class Messagerie
 *
 * Represents the messagerie table.
 * Composite PK: (membre_commission_id, etudiant_concerne_id, discussion_id, date_message).
 *
 * @package App\Models
 */
class Messagerie extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'messagerie';

    /**
     * @var string FK to enseignant.utilisateur_id, part of CPK.
     */
    public string $membre_commission_id;

    /**
     * @var string FK to etudiant.utilisateur_id, part of CPK.
     */
    public string $etudiant_concerne_id;

    /**
     * @var string FK to discussion.id, part of CPK.
     */
    public string $discussion_id;

    /**
     * @var string|null The message content.
     */
    public ?string $message; // DDL TEXT

    /**
     * @var string The date of the message (part of CPK).
     */
    public string $date_message; // DDL DATETIME

    /**
     * Messagerie constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
