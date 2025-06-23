<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Action;
use App\Models\Traitement;
use App\Models\Utilisateur;
use PDO;

/**
 * Class Audit
 *
 * Represents the audit table.
 *
 * @package App\Models
 */
class Audit extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'audit';

    /**
     * @var int|null The ID of the audit entry (auto-incrementing).
     */
    public ?int $id;

    /**
     * @var string|null The ID of the user who performed the action (FK to utilisateur.id).
     */
    public ?string $utilisateur_id;

    /**
     * @var string|null The ID of the action related to this audit entry.
     */
    public ?string $action_id;

    /**
     * @var string|null The ID of the traitement related to this audit entry.
     */
    public ?string $traitement_id;

    /**
     * @var string|null The action performed.
     */
    public ?string $action_effectuee;

    /**
     * @var string|null The timestamp of the action.
     */
    public ?string $timestamp_action; // Assuming DATETIME or TIMESTAMP

    /**
     * @var string|null The IP address from which the action was performed.
     */
    public ?string $adresse_ip;

    /**
     * Audit constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }



}
