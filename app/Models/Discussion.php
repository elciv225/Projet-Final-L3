<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\Messagerie; // Added import
use PDO;

/**
 * Class Discussion
 *
 * Represents the discussion table.
 *
 * @package App\Models
 */
class Discussion extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'discussion';

    /**
     * @var string The ID of the discussion.
     */
    public string $id;

    /**
     * @var string|null The content of the message.
     */
    public ?string $message;

    /**
     * @var string|null The timestamp of the message.
     */
    public ?string $timestamp_message; // Assuming DATETIME or TIMESTAMP SQL type maps to string

    /**
     * @var string|null The ID of the sender (utilisateur).
     */
    public ?string $id_utilisateur_expediteur;

    /**
     * @var string|null The ID of the recipient (utilisateur).
     */
    public ?string $id_utilisateur_destinataire;

    /**
     * @var string|null The ID of the related report (rapport_etudiant).
     */
    public ?string $id_rapport_etudiant;


    /**
     * Discussion constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    }
