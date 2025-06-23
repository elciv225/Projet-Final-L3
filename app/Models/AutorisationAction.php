<?php

namespace App\Models;

use App\Database\BaseModel;
use App\Models\GroupeUtilisateur;
use App\Models\Traitement;
use App\Models\Action;
use PDO;

/**
 * Class AutorisationAction
 *
 * Represents the autorisation_action table.
 * Composite PK: (groupe_utilisateur_id, traitement_id, action_id).
 *
 * @package App\Models
 */
class AutorisationAction extends BaseModel
{
    /**
     * @var string The database table name.
     */
    protected string $table = 'autorisation_action';

    /**
     * @var string The ID of the groupe_utilisateur (FK, part of CPK).
     */
    public string $groupe_utilisateur_id;

    /**
     * @var string The ID of the traitement (FK, part of CPK).
     */
    public string $traitement_id;

    /**
     * @var string The ID of the action (FK, part of CPK).
     */
    public string $action_id;

    /**
     * AutorisationAction constructor.
     *
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    }
